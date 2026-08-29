<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ResellerCommission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get overall financial and order analytics for a given period.
     *
     * @param string $period 'today', 'yesterday', 'this_week', 'this_month', 'this_year', 'all', 'custom'
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array<string, mixed>
     */
    public function getAnalyticsSummary(string $period = 'this_month', ?string $startDate = null, ?string $endDate = null): array
    {
        [$start, $end] = $this->resolveDateRange($period, $startDate, $endDate);

        $orderQuery = Order::query();
        if ($start && $end) {
            $orderQuery->whereBetween('created_at', [$start, $end]);
        }

        $allOrders = (clone $orderQuery)->get();
        $paidOrCompletedOrders = (clone $orderQuery)->whereIn('status', [
            OrderStatus::PAID,
            OrderStatus::PROCESSING,
            OrderStatus::SHIPPED,
            OrderStatus::DELIVERED,
            OrderStatus::COMPLETED,
        ])->get();

        $totalGmv = $paidOrCompletedOrders->sum('grand_total');
        $totalSubtotal = $paidOrCompletedOrders->sum('subtotal');
        $totalShipping = $paidOrCompletedOrders->sum('shipping_cost');
        $totalDiscounts = $paidOrCompletedOrders->sum(fn ($o) => $o->coupon_discount + $o->points_discount + $o->discount_amount);

        $totalOrdersCount = $allOrders->count();
        $paidOrdersCount = $paidOrCompletedOrders->count();
        $completedOrdersCount = $allOrders->where('status', OrderStatus::COMPLETED)->count();
        $cancelledOrdersCount = $allOrders->where('status', OrderStatus::CANCELLED)->count();

        $aov = $paidOrdersCount > 0 ? (int) round($totalGmv / $paidOrdersCount) : 0;
        $fulfillmentRate = $paidOrdersCount > 0 ? round(($completedOrdersCount / $paidOrdersCount) * 100, 1) : 0;

        // Sales Trend Data for Charting
        $salesTrend = $this->getSalesTrend($start, $end);

        // Top Selling Products
        $topProducts = $this->getTopSellingProducts($start, $end);

        // Inventory Alerts
        $lowStockVariants = ProductVariant::with('product')
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(10)
            ->get();

        $outOfStockCount = ProductVariant::where('stock', '<=', 0)->count();

        // Reseller Affiliates Performance
        $topResellers = $this->getTopResellers($start, $end);

        return [
            'period' => $period,
            'start_date' => $start?->toDateString(),
            'end_date' => $end?->toDateString(),
            'total_gmv' => $totalGmv,
            'formatted_gmv' => 'Rp ' . number_format($totalGmv, 0, ',', '.'),
            'total_subtotal' => $totalSubtotal,
            'formatted_subtotal' => 'Rp ' . number_format($totalSubtotal, 0, ',', '.'),
            'total_shipping' => $totalShipping,
            'formatted_shipping' => 'Rp ' . number_format($totalShipping, 0, ',', '.'),
            'total_discounts' => $totalDiscounts,
            'formatted_discounts' => 'Rp ' . number_format($totalDiscounts, 0, ',', '.'),
            'total_orders' => $totalOrdersCount,
            'paid_orders' => $paidOrdersCount,
            'completed_orders' => $completedOrdersCount,
            'cancelled_orders' => $cancelledOrdersCount,
            'aov' => $aov,
            'formatted_aov' => 'Rp ' . number_format($aov, 0, ',', '.'),
            'fulfillment_rate' => $fulfillmentRate,
            'sales_trend' => $salesTrend,
            'top_products' => $topProducts,
            'low_stock_variants' => $lowStockVariants,
            'out_of_stock_count' => $outOfStockCount,
            'top_resellers' => $topResellers,
        ];
    }

    /**
     * Resolve start and end date for filtering.
     */
    protected function resolveDateRange(string $period, ?string $startDate, ?string $endDate): array
    {
        return match ($period) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'yesterday' => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
            'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'this_year' => [now()->startOfYear(), now()->endOfYear()],
            'custom' => [
                $startDate ? Carbon::parse($startDate)->startOfDay() : now()->startOfMonth(),
                $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfDay(),
            ],
            default => [null, null],
        };
    }

    /**
     * Get Sales Revenue aggregated by date for chart rendering.
     */
    protected function getSalesTrend(?Carbon $start, ?Carbon $end): array
    {
        $startDate = $start ?? now()->subDays(14)->startOfDay();
        $endDate = $end ?? now()->endOfDay();

        $labels = [];
        $revenue = [];
        $orderCounts = [];

        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $dateStr = $current->toDateString();
            $labels[] = $current->format('d M');

            $dayOrders = Order::whereDate('created_at', $dateStr)
                ->whereIn('status', [
                    OrderStatus::PAID,
                    OrderStatus::PROCESSING,
                    OrderStatus::SHIPPED,
                    OrderStatus::DELIVERED,
                    OrderStatus::COMPLETED,
                ])
                ->get();

            $revenue[] = (int) $dayOrders->sum('grand_total');
            $orderCounts[] = $dayOrders->count();

            $current->addDay();
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'orders' => $orderCounts,
        ];
    }

    /**
     * Get top selling product items.
     */
    protected function getTopSellingProducts(?Carbon $start, ?Carbon $end): Collection
    {
        $query = OrderItem::select(
            'product_name',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(subtotal) as total_revenue')
        )->groupBy('product_name')
         ->orderByDesc('total_qty')
         ->take(5);

        if ($start && $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }

        return $query->get();
    }

    /**
     * Get top affiliate resellers.
     */
    protected function getTopResellers(?Carbon $start, ?Carbon $end): Collection
    {
        $query = ResellerCommission::with('reseller.resellerProfile')
            ->select(
                'reseller_id',
                DB::raw('COUNT(id) as total_referral_orders'),
                DB::raw('SUM(subtotal) as total_sales_volume'),
                DB::raw('SUM(commission_amount) as total_commissions')
            )
            ->whereIn('status', ['available', 'paid'])
            ->groupBy('reseller_id')
            ->orderByDesc('total_sales_volume')
            ->take(5);

        if ($start && $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }

        return $query->get();
    }
}
