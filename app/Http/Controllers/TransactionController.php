<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function transaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'total_transaction' => 'required|numeric',
            'status_transaction' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->fails()
            ], 401);
        }

        $codeTransaction = date('dmY') . floor(rand(1, 99)) . uniqid();
        $transaction = Transaction::create([
            'user_id' => $request->user_id,
            'code_transaction' => $codeTransaction,
            'total_transaction' => $request->total_transaction,
            'status_transaction' => $request->status_transaction,
        ]);
        $detailTransactions = DetailTransaction::create([
            'transaction_id' => $transaction->id,
            'user_id' => $transaction->user_id,
            'product_id' => $request->product_id,
            'order_number' => $codeTransaction . '-' . "NO" . rand(0, 999999),
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total' => $request->total
        ]);

        return response()->json([
            'success' => true,
            'transaction' => $transaction,
            'detailTransactions' => $detailTransactions
        ], 201);
    }
}
