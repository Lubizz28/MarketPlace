<?php

namespace App\Services\Notification;

use App\Contracts\WhatsAppGatewayInterface;
use App\Models\BroadcastLog;
use App\Models\BroadcastMessage;
use App\Models\Order;
use App\Models\ResellerWithdrawal;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function __construct(
        protected WhatsAppGatewayInterface $whatsAppGateway
    ) {}

    /**
     * Send Order Placed notification to buyer.
     */
    public function sendOrderPlacedNotification(Order $order): void
    {
        $message = "Assalamu'alaikum {$order->customer_name},\n\n"
            . "Terima kasih telah berbelanja di *MedinaStyle*! Pesanan Anda telah kami terima:\n"
            . "• *No. Pesanan:* #{$order->order_number}\n"
            . "• *Total Tagihan:* {$order->formatted_grand_total}\n"
            . "• *Status:* Menunggu Pembayaran\n\n"
            . "Silakan selesaikan pembayaran sebelum tagihan kedaluwarsa:\n"
            . url("/orders/{$order->order_number}") . "\n\n"
            . "Jazakallahu khairan atas kepercayaannya.";

        if ($order->customer_phone) {
            $this->whatsAppGateway->sendMessage($order->customer_phone, $message);
        }
    }

    /**
     * Send Payment Success notification to buyer.
     */
    public function sendPaymentSuccessNotification(Order $order): void
    {
        $message = "Alhamdulillah {$order->customer_name}!\n\n"
            . "Pembayaran untuk pesanan *#{$order->order_number}* sebesar *{$order->formatted_grand_total}* telah kami terima.\n\n"
            . "Pesanan Anda saat ini sedang kami siapkan dan segera dikirimkan ke alamat Anda. Cek status pesanan secara berkala:\n"
            . url("/orders/{$order->order_number}") . "\n\n"
            . "Terima kasih, *MedinaStyle*.";

        if ($order->customer_phone) {
            $this->whatsAppGateway->sendMessage($order->customer_phone, $message);
        }
    }

    /**
     * Send Order Shipped with Tracking Number to buyer.
     */
    public function sendOrderShippedNotification(Order $order): void
    {
        $courier = $order->shipment?->courier_name ?? 'Kurir';
        $tracking = $order->shipment?->tracking_number ?? 'Segera Diperbarui';

        $message = "Kabar gembira {$order->customer_name}!\n\n"
            . "Pesanan busana muslimah Anda *#{$order->order_number}* telah diberangkatkan:\n"
            . "• *Ekspedisi:* {$courier}\n"
            . "• *No. Resi:* *{$tracking}*\n\n"
            . "Lacak perjalanan paket Anda di:\n"
            . url("/orders/{$order->order_number}") . "\n\n"
            . "Semoga busana yang dipesan bermanfaat & barakah. *MedinaStyle*.";

        if ($order->customer_phone) {
            $this->whatsAppGateway->sendMessage($order->customer_phone, $message);
        }
    }

    /**
     * Send Reseller Payout status notification.
     */
    public function sendWithdrawalProcessedNotification(ResellerWithdrawal $withdrawal): void
    {
        $user = $withdrawal->user;
        if (!$user) {
            return;
        }

        if ($withdrawal->status->value === 'paid') {
            $message = "Alhamdulillah Mitra Reseller {$user->name},\n\n"
                . "Penarikan saldo komisi *#{$withdrawal->withdrawal_number}* sebesar *{$withdrawal->formatted_amount}* telah sukses ditransfer ke rekening {$withdrawal->bank_name} ({$withdrawal->bank_account_number}).\n\n"
                . "Pantau buku kas dompet Anda di:\n"
                . url('/reseller/wallet') . "\n\n"
                . "Terima kasih atas kerja sama luar biasanya! *MedinaStyle Hub*.";
        } else {
            $message = "Pemberitahuan Mitra Reseller {$user->name},\n\n"
                . "Pengajuan penarikan dana *#{$withdrawal->withdrawal_number}* tidak dapat diproses (Status: {$withdrawal->status->label()}).\n"
                . "Saldo Anda telah dikembalikan seutuhnya ke dompet kas reseller.\n\n"
                . "Keterangan: " . ($withdrawal->notes ?? '-') . "\n\n"
                . url('/reseller/withdrawals');
        }

        if ($user->phone) {
            $this->whatsAppGateway->sendMessage($user->phone, $message);
        }
    }

    /**
     * Dispatch broadcast messages to targeted audience.
     */
    public function sendBroadcastMessage(BroadcastMessage $broadcast): int
    {
        $query = User::query()->where('status', 'active');

        if ($broadcast->target_role === 'member') {
            $query->where('role', 'member');
        } elseif ($broadcast->target_role === 'reseller') {
            $query->where('role', 'reseller');
        }

        $recipients = $query->get();
        $dispatchedCount = 0;

        foreach ($recipients as $recipient) {
            // WhatsApp dispatch
            if ($broadcast->channel === 'whatsapp' || $broadcast->channel === 'both') {
                if ($recipient->phone) {
                    $waText = "*[MedinaStyle Promo]* {$broadcast->title}\n\n"
                        . "Assalamu'alaikum {$recipient->name},\n\n"
                        . "{$broadcast->message}\n\n"
                        . "Kunjungi toko kami: " . url('/') . "\n"
                        . "_Untuk berhenti berlangganan, balas STOP._";

                    $res = $this->whatsAppGateway->sendMessage($recipient->phone, $waText);

                    BroadcastLog::create([
                        'broadcast_id' => $broadcast->id,
                        'user_id' => $recipient->id,
                        'recipient_name' => $recipient->name,
                        'recipient_target' => $recipient->phone,
                        'channel' => 'whatsapp',
                        'status' => $res['success'] ? 'sent' : 'failed',
                    ]);

                    $dispatchedCount++;
                }
            }

            // Email log simulation
            if ($broadcast->channel === 'email' || $broadcast->channel === 'both') {
                if ($recipient->email) {
                    BroadcastLog::create([
                        'broadcast_id' => $broadcast->id,
                        'user_id' => $recipient->id,
                        'recipient_name' => $recipient->name,
                        'recipient_target' => $recipient->email,
                        'channel' => 'email',
                        'status' => 'sent',
                    ]);

                    $dispatchedCount++;
                }
            }
        }

        $broadcast->update([
            'total_recipients' => $dispatchedCount,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return $dispatchedCount;
    }
}
