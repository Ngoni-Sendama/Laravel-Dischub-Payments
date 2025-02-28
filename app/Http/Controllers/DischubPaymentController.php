<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Services\DischubService;
use Illuminate\Support\Facades\Log;
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
        $statuses = Status::latest()->get();
        return view('orders', compact('transactions', 'statuses'));
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
            'reference' => 'required|string|unique:orders,reference', // added reference field
        ]);

        // Passing the validated data including the reference to the createOrder method
        $response = $this->dischubService->createOrder($validated);

        if ($response['status'] === 'success') {
            return redirect($this->dischubService->getPaymentUrl($validated['recipient'], $validated['order_id']));
        }

        return back()->with('error', $response['message']);
    }

    public function receiveDischubCallback(Request $request)
    {
        if ($request->isMethod('post')) {
            // Ensure request data is properly parsed as JSON
            $data = json_decode($request->getContent(), true);

            // Log the received callback data
            Log::info('Received data at callback:', $data ?? []);

            Status::create([
                'data' => json_encode($data) // Store as JSON string
            ]);

            // Return the received data in JSON format
            return response()->json([
                'message' => 'Callback received successfully',
                'data' => $data ?? []
            ], 200);
        }

        return response()->json(['error' => 'Invalid request method'], 405);
    }
}
