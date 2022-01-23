<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function transaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'requred|numeric',
            'total_transaction' => 'requred|numeric',
            'status_transaction' => 'requred|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->fails()
            ], 401);
        }

        $transaction = Transaction::create([
            'user_id' => $request->user_id,
            'total_transaction' => $request->total_transaction,
            'status_transaction' => $request->status_transaction,
        ]);

        return response()->json([
            'success' => true,
            'transaction' => $transaction
        ], 201);
    }
}
