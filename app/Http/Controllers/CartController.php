<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;


class CartController extends Controller
{
    
    function addToCart(Request $request, $product_id)
    {

        $product_in_cart = Cart::where('user_id', auth()->user()->id)
                ->where('product_id', $product_id)
                ->count();

        if($product_in_cart > 0){

            return response()->json([
                'status' => 406,
                'message' => "This product is already in you cart!",
            ]);

        }else{

            Cart::create([
                'user_id'=> auth()->user()->id,
                'product_id'=> $product_id,
                'quantity'=> $request->quantity,
            ]);
            
            return response()->json([
                'status' => 200,
                'message' => "Added to cart successfully",
            ]);
        }
    }

}
