<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SellerRequest;

class SellerRequestController extends Controller
{
    // Store seller request (user must be logged in)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $sellerRequest = SellerRequest::create([
            'user_id' => Auth::id(), // ✅ Direct from token
            'shop_name' => $validated['shop_name'],
            'category_id' => $validated['category_id'],
            'subcategory_id' => $validated['subcategory_id'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Your request has been sent to admin for approval!',
            'data' => $sellerRequest
        ], 201);
    }

    // Admin: Get all pending requests
    public function indexPending()
    {
        $requests = SellerRequest::where('status', 'pending')
            ->with(['user:id,name,email', 'category:id,name', 'subcategory:id,name'])
            ->get();

        return response()->json($requests);
    }

    // Admin: Approve request
    public function approve($id)
    {
        $requestItem = SellerRequest::findOrFail($id);
        $requestItem->status = 'approved';
        $requestItem->save();

        return response()->json(['message' => 'Seller request approved successfully.']);
    }

    // Admin: Reject request
    public function reject($id)
    {
        $requestItem = SellerRequest::findOrFail($id);
        $requestItem->status = 'rejected';
        $requestItem->save();

        return response()->json(['message' => 'Seller request rejected successfully.']);
    }

    public function getAllowedCategoriesByUser(Request $request)
{
    $user = $request->user();

    $requests = $user->sellerRequest()->where('status', 'approved')->get();

    $categories = $requests->map(function($req){
        return [
            'id' => $req->category ? $req->category->id : null,
            'name' => $req->category ? $req->category->name : 'Unknown',
            'subcategories' => $req->subcategory ? [
                [
                    'id' => $req->subcategory->id,
                    'name' => $req->subcategory->name
                ]
            ] : []
        ];
    })->filter(fn($cat) => $cat['id'] !== null) // remove nulls
      ->unique('id')
      ->values();

    return response()->json([
        'categories' => $categories
    ]);
}

    public function index()
{
    $requests = SellerRequest::where('status', 'pending')
        ->with(['user', 'category', 'subcategory'])
        ->get();

    return view('admin.sellerrequests', compact('requests'));
}

// public function myproduct(Request $request)
//     {
//         $sellerId = $request->user()->id;

//         $products = Product::where('user_id', $sellerId)
//             ->orderBy('created_at', 'desc')
//             ->get();

//         return response()->json($products);
//     }

    public function order(Request $request)
    {
        $sellerId = $request->user()->id;

        // Orders jahan order_items ke product ka owner current seller hai
        $orders = Order::whereHas('items.product', function ($q) use ($sellerId) {
            $q->where('user_id', $sellerId);
        })
        ->with(['items.product' => function ($q) use ($sellerId) {
            $q->where('user_id', $sellerId);
        }])
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json($orders);
    }
}
