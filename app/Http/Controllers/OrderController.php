<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        return view('user');
    }

    public function payment(Request $request)
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => 20000,
            ),
            'item_details' => [
                [
                    "id" => "a01",
                    "price" => 10000,
                    "quantity" => 1,
                    "name" => "Apple"
                ],
                [
                    "id" => "a02",
                    "price" => 10000,
                    "quantity" => 1,
                    "name" => "Anggur"
                ]

            ],
            'customer_details' => array(
                'first_name' => $request->name,
                'last_name' => '',
                'email' => $request->email,
                'phone' => $request->phone,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return view('payment', compact('snapToken'));
    }

    public function payment_post(Request $request)
    {
        $json = json_decode($request->json);
        $order = new Order();
        $order->status = $json->transaction_status;
        $order->transaction_id = $json->transaction_id;
        $order->order_id = $json->order_id;
        $order->name = $request->name;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->gross_amount = $json->gross_amount;
        $order->payment_type = $json->payment_type;
        $order->payment_code = isset($json->payment_code) ? $json->payment_code : null;
        $order->pdf_url = isset($json->pdf_url) ? $json->pdf_url : null;
        return $order->save() ? redirect('/')->with('alert-success', 'Order Berhasil') : redirect('/')->with('alert-danger', 'Order Gagal');
    }

    public function payment_handler(Request $request)
    {
        $json = json_decode($request->getContent());
        $signaturekey = hash('sha512', $json->order_id . $json->status_code . $json->gross_amount . env('MIDTRANS_SERVER_KEY'));
        if ($signaturekey != $json->signature_key) {
            return abort(404);
        }
        $order = Order::where('order_id', $json->order_id)->firstOrFail();
        return $order->update(['status' => $json->transaction_status]);
    }
}
