<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DischubPaymentController extends Controller
{
    public function handleCallback(Request $request)
    {
        // Log the incoming request
        Log::info('Dischub Callback Received', $request->all());

        // Validate the request (ensure it has the expected parameters)
        $request->validate([
            'transaction_id' => 'required|string',
            'status' => 'required|string',
            'amount' => 'required|numeric',
            'reference' => 'nullable|string',
        ]);

        // Process payment status
        $transactionId = $request->transaction_id;
        $status = $request->status;
        $amount = $request->amount;
        $reference = $request->reference;

        // Update transaction status in the database
        $payment = \App\Models\Payment::where('transaction_id', $transactionId)->first();

        if ($payment) {
            $payment->status = $status;
            $payment->save();
        }

        return response()->json(['message' => 'Callback received successfully'], 200);
    }
}