<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart; 
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;


class OrderController extends Controller
{
    
    function getOrderTotalPrice(){

        $user_id = auth()->user()->id;

        $total_price = DB::table('cart')
        ->join('products', 'cart.product_id', '=', 'products.id')
        ->where('cart.user_id', $user_id)
        ->sum(DB::raw('products.price * cart.quantity'));

        return response()->json([
            'status' => 200,
            'total_price' => $total_price
        ]);
    }


    function placeOrder(Request $request){
        
        $user_id = auth()->user()->id;

        $cart_data = Cart::where('user_id', $user_id)->get();

        $total_price = DB::table('cart')
        ->join('products', 'cart.product_id', '=', 'products.id')
        ->where('cart.user_id', $user_id)
        ->sum(DB::raw('products.price * cart.quantity'));

        $order = Order::create([
            'user_id' => $user_id,
            'address' => $request->address,
            'city' => $request->city,
            'phone' => $request->phone,
            'total_amount' => $total_price
        ]);

        foreach($cart_data as $cart){

            $order_products = new OrderProduct;
            $order_products->order_id = $order->id;
            $order_products->product_id = $cart->product_id;
            $order_products->quantity = $cart->quantity;
            $order_products->save();

            Product::where('id', $cart->product_id)->decrement('quantity', $cart->quantity);
            
            Cart::where('user_id', $user_id)->delete();

        }
        
        return response()->json([
            'status' => 200,
            'message' => "Your order has been placed successfully",
        ]);
    }


    function getUserOrders(){

        $user_id = auth()->user()->id;

        $user_orders = DB::table('orders')
        ->join('order_products', 'order_products.order_id', '=', 'orders.id')
        ->join('products', 'order_products.product_id', '=', 'products.id')
        ->where('orders.user_id', $user_id)
        ->get();

        if($user_orders){

            return response()->json([
                'status' => 200,
                'user_orders' =>  $user_orders
            ]);

        }else{

            return response()->json([
                'status' => 401,
                'message' =>  "You don't have any order yet"
            ]);
        }
    }


    function getAllOrders(){

        $orders = Order::all();

        return response()->json([
            'status' => 200,
            'orders' =>  $orders
        ]);
    }


    function getOrderProducts($id){

        $order_products = DB::table('orders')
        ->join('order_products', 'order_products.order_id', '=', 'orders.id')
        ->join('products', 'order_products.product_id', '=', 'products.id')
        ->where('orders.id', $id)
        ->get();

        return response()->json([
            'status' => 200,
            'order_products' =>  $order_products
        ]);
    }
}
