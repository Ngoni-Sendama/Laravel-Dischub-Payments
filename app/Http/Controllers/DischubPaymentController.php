<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\DischubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DischubPaymentController extends Controller
{
    protected DischubService $dischubService;

    public function __construct(DischubService $dischubService)
    {
        $this->dischubService = $dischubService;
    }

    /**
     * Display a list of all orders.
     */
    public function listOrders()
    {
        $transactions = Order::latest()->get();
        return view('orders', compact('transactions'));
    }

    /**
     * Create an order and redirect to payment.
     */
    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'order_id'  => 'required|numeric|unique:orders,order_id',
            'sender'    => 'required|email',
            'recipient' => 'required|email',
            'amount'    => 'required|numeric|min:1|max:480',
            'currency'  => 'required|in:USD,ZWG',
        ]);

        $response = $this->dischubService->createOrder($validated);

        if ($response['status'] === 'success') {
            return redirect($this->dischubService->getPaymentUrl($validated['recipient'], $validated['order_id']));
        }

        return back()->with('error', $response['message']);
    }

    /**
     * Manually trigger the Dischub callback for an order.
     */
    public function triggerCallback($orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        return redirect()->route('dischub.callback', ['order_id' => $order->order_id]);
    }

    /**
     * Handle Dischub callback notifications and update order status.
     */
    public function handleCallback(Request $request)
    {
        $data = $request->validate([
            'transaction_id' => 'required|numeric',
            'order_id'       => 'required|numeric|exists:orders,order_id',
            'status'         => 'required|string|in:success,failed',
            'currency'       => 'required|string',
            'amount'         => 'required|numeric',
            'timestamp'      => 'required|date',
        ]);

        // Log the received data for debugging (optional but recommended)
        \Log::info('Dischub Callback Received:', $data);

        // Find the order
        $order = Order::where('order_id', $data['order_id'])->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update order status
        $order->update([
            'status'  => $data['status'],
            'paid_at' => $data['status'] === 'success' ? now() : null,
        ]);

        return response()->json(['message' => 'Order updated successfully'], 200);
    }


    public function checkStatus(Request $request, $order_id)
    {
        // Fetch the order from the database
        $order = Order::where('order_id', $order_id)->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }

        // Inform the user that the status is updated via callback
        return redirect()->back()->with('info', 'Payment status is updated via callback. Please wait for the notification.');
    }
}
