<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class PaymentController extends Controller
{
    /**
     * Handle Midtrans webhook notification.
     * Updates order status based on payment result.
     */
    public function handleNotification(Request $request)
    {
        // Set Midtrans config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        try {
            // Get notification data
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            // Find the order
            $order = Order::findOrFail($orderId);

            // Update status based on transaction
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $order->update(['status' => 'confirmed']); // Payment success
                } else if ($fraudStatus == 'challenge') {
                    $order->update(['status' => 'pending']); // Challenge by fraud detection
                } else {
                    $order->update(['status' => 'cancelled']); // Other fraud status
                }
            } elseif ($transactionStatus == 'settlement') {
                $order->update(['status' => 'confirmed']); // Settlement success
            } elseif ($transactionStatus == 'pending') {
                $order->update(['status' => 'pending']); // Still pending
            } elseif ($transactionStatus == 'deny') {
                $order->update(['status' => 'cancelled']); // Payment denied
            } elseif ($transactionStatus == 'expire') {
                $order->update(['status' => 'cancelled']); // Expired
            } elseif ($transactionStatus == 'cancel') {
                $order->update(['status' => 'cancelled']); // Cancelled
            }

            // Log for debugging (optional, remove in production)
            \Log::info('Midtrans notification received for order ' . $orderId . ': ' . $transactionStatus);

            return response('OK', 200); // Midtrans expects plain text 'OK'

        } catch (\Exception $e) {
            \Log::error('Midtrans notification error: ' . $e->getMessage());
            return response('Error', 500);
        }
    }
}
