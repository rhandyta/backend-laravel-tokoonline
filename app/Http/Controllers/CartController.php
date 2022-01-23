<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $user = DB::table('carts', $request->user_id)->where('product_id', $request->product_id)->first();
        if (count($request->all()) < 1) {
            return response()->json([
                'success' => false,
                'message' => 'fail to input cart'
            ]);
        }
        if (!empty($user->product_id)) {
            return response()->json([
                'success' => false,
                'message' => 'you have same product'
            ]);
        }
        $cart = Cart::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);
        return response()->json([
            'success' => true,
            'carts' => $cart
        ]);
    }
}
