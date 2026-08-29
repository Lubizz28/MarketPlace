<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ResellerCommission;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminAnalyticsController extends Controller
{
    public function __construct(
        protected AnalyticsService $analyticsService
    ) {}

    /**
     * Executive Sales & Operations Analytics Dashboard.
     */
    public function index(Request $request): View
    {
        $period = $request->input('period', 'this_month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $analytics = $this->analyticsService->getAnalyticsSummary($period, $startDate, $endDate);

        return view('admin.analytics.index', compact('analytics', 'period'));
    }

    /**
     * Export Orders to CSV file format.
     */
    public function exportOrders(Request $request): StreamedResponse
    {
        $period = $request->input('period', 'this_month');
        $analytics = $this->analyticsService->getAnalyticsSummary($period, $request->input('start_date'), $request->input('end_date'));

        $filename = "laporan-penjualan-medinastyle-{$period}-" . date('Ymd-His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($analytics) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No. Pesanan',
                'Tanggal',
                'Nama Pembeli',
                'Email',
                'No. HP',
                'Status Pesanan',
                'Subtotal (Rp)',
                'Diskon Kupon (Rp)',
                'Diskon Poin (Rp)',
                'Ongkir (Rp)',
                'Grand Total (Rp)',
                'Ekspedisi',
                'Metode Pembayaran',
            ]);

            $orderQuery = Order::with(['shipment', 'payment'])->latest();
            if ($analytics['start_date'] && $analytics['end_date']) {
                $orderQuery->whereBetween('created_at', [$analytics['start_date'], $analytics['end_date'] . ' 23:59:59']);
            }

            foreach ($orderQuery->cursor() as $order) {
                fputcsv($handle, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    $order->status->label(),
                    $order->subtotal,
                    $order->coupon_discount,
                    $order->points_discount,
                    $order->shipping_cost,
                    $order->grand_total,
                    $order->shipment?->courier_name ?? '-',
                    $order->payment?->payment_method->label() ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
