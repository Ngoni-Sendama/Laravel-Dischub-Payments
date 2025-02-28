<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DischubService
{
    protected string $apiUrl = 'https://dischub.co.zw/api/orders/create/';
    protected string $paymentUrl = 'https://dischub.co.zw/api/make/payment/to/';
    protected string $notifyUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('DISCHUB_API_KEY');
        $this->notifyUrl = 'https://mcsolutionszim.co.zw/dischub/callback';
    }

    /**
     * Create an order, store it in the database, and send it to Dischub API.
     */
    public function createOrder(array $data)
    {
        $order = Order::create([
            'order_id'        => $data['order_id'],
            'sender_email'    => $data['sender'],
            'recipient_email' => 'ngonidzashesendama@gmail.com',
            'amount'          => $data['amount'],
            'currency'        => $data['currency'],
            'status'          => 'pending',
        ]);

        $response = Http::post($this->apiUrl, [
            'api_key'    => $this->apiKey,
            'notify_url' => $this->notifyUrl,
            'order_id'   => $order->order_id,
            'sender'     => $order->sender_email,
            'recipient'  => 'ngonidzashesendama@gmail.com',
            'amount'     => $order->amount,
            'currency'   => $order->currency,
        ]);

        return $response->json();
    }

    /**
     * Get the payment URL for an order.
     */
    public function getPaymentUrl(string $recipient, int $orderId): string
    {
        return "{$this->paymentUrl}{$recipient}/{$orderId}";
    }

    /**
     * Handle the Dischub callback and update order status.
     */
    public function handleCallback(array $data)
    {
        Log::info('Dischub Callback Received', $data);

        $order = Order::where('order_id', $data['order_id'])->first();

        if ($order) {
            $order->update([
                'status'  => $data['status'],
                'paid_at' => now(),
            ]);
        }

        return ['message' => 'Payment status received'];
    }
}
