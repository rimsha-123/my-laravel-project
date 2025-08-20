<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
        ]);
    
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => 0,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->shipping_address,
            ]);
    
            $total = 0;
    
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    $order->delete();
                    return response()->json(['error' => 'Product not found: '.$item['product_id']], 404);
                }
    
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
    
                $total += $product->price * $item['quantity'];
            }
    
            $order->update(['total_amount' => $total]);
    
            return response()->json([
                'message' => 'Order placed successfully!',
                'order' => $order->load('items')
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    
//     public function myOrders(Request $request)
// {
//     $user = $request->user(); // current logged-in user
//     $orders = Order::with(['items.product']) // eager load items + product
//         ->where('user_id', $user->id) // ✅ sirf is user ke orders
//         ->get();

//     return response()->json($orders);
// }

// OrderController.php
// public function sellerOrders(Request $request)
// {
//     $sellerId = $request->user()->id;

//     $orders = Order::whereHas('items.product', function($q) use ($sellerId) {
//         $q->where('user_id', $sellerId); // yaha product ka owner seller hoga
//     })
//     ->with(['items.product'])
//     ->get();

//     return response()->json($orders);
// }



public function sellerOrders(Request $request)
{
    $sellerId = $request->user()->id;

    $orders = Order::whereHas('items.product', function($q) use ($sellerId) {
        $q->where('user_id', $sellerId);
    })
    ->with(['items.product' => function($q) {
        $q->select('id', 'name', 'image', 'user_id'); // image field ensure ho
    }])
    ->get();

    // Image ko full URL banake bhejna
    $orders->each(function ($order) {
        $order->items->each(function ($item) {
            if ($item->product && $item->product->image) {
                $item->product->image = asset($item->product->image);
            }
        });
    });

    return response()->json($orders);
}

}

