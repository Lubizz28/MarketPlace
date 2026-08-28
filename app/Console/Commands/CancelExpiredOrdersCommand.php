<?php

namespace App\Console\Commands;

use App\Actions\Order\UpdateOrderStatusAction;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelExpiredOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'orders:cancel-expired';

    /**
     * The console command description.
     */
    protected $description = 'Automatically cancel pending orders that have exceeded their 24-hour payment expiration window and restore inventory.';

    public function handle(UpdateOrderStatusAction $updateOrderStatusAction): int
    {
        $this->info('Scanning for expired pending orders...');

        $expiredOrders = Order::where('status', OrderStatus::PENDING_PAYMENT)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->with(['items.variant', 'payment'])
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('No expired orders found.');
            return self::SUCCESS;
        }

        $count = 0;
        foreach ($expiredOrders as $order) {
            try {
                $updateOrderStatusAction->execute(
                    order: $order,
                    targetStatus: OrderStatus::CANCELLED,
                    reason: 'Otomatis dibatalkan sistem: Batas waktu pembayaran 24 jam telah berakhir'
                );

                $count++;
                $this->line("Cancelled order: #{$order->order_number}");
            } catch (\Throwable $e) {
                Log::error("Failed to auto-cancel expired order #{$order->order_number}: " . $e->getMessage());
                $this->error("Failed to cancel #{$order->order_number}: {$e->getMessage()}");
            }
        }

        $this->info("Successfully cancelled {$count} expired orders and restored stock.");
        return self::SUCCESS;
    }
}
