<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use App\Models\Transaction;
use App\Notifications\CheckoutNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class TransactionController extends Controller
{


    public function index()
    {
        $transaction = Transaction::where('user_id', Auth('sanctum')->user()->id)->get();
        return response()->json($transaction);
    }

    public function show($orderNumber)
    {
        $getSingleOrder = DetailTransaction::where('order_number', $orderNumber)->firstOrFail();
        return response()->json($getSingleOrder);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            'user_id' => Auth('sanctum')->user()->id,
            'code_transaction' => $codeTransaction,
            'total_transaction' => $request->total_transaction,
            'status_transaction' => $request->status_transaction,
        ]);

        $detailTransactions = DetailTransaction::create([
            'transaction_id' => $transaction->id,
            'user_id' => Auth('sanctum')->user()->id,
            'product_id' => $request->product_id,
            'order_number' => $codeTransaction . '-' . "NO" . rand(0, 999999),
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total' => $request->price * $request->quantity
        ]);
        $user = Auth('sanctum')->user();
        $delay = now()->addMinutes(1);
        Notification::sendNow($user, (new CheckoutNotification($detailTransactions))->delay($delay));

        return response()->json([
            'success' => true,
            'transaction' => $transaction,
            'detailTransactions' => $detailTransactions
        ], 201);
    }
}
