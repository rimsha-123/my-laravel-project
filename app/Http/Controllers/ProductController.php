<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Favorite;

class ProductController extends Controller
{
    // ✅ Apply Sanctum auth middleware for secure routes
    public function __construct()
    {
        // Only authenticated users can add products
        $this->middleware('auth:sanctum')->only(['storeSimple']);
    }

    // API: list products (filter by subcategory)
    public function index(Request $request)
    {
        try {
            $subcategoryId = $request->query('subcategory_id');
    
            $products = $subcategoryId 
                ? Product::where('subcategory_id', $subcategoryId)->get()
                : Product::all();
    
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Admin panel: show products page
    public function show()
    {
        $products = Product::all();
        $categories = Category::all();
        $subcategories = Subcategory::all();

        return view('admin.products', compact('products', 'categories', 'subcategories'));
    }

    // API: add product (only authenticated)
    public function storeSimple(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'color' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'youtube_link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = new Product();
        $product->fill($request->only([
            'name', 'description', 'price', 'stock', 'color',
            'category_id', 'subcategory_id', 'youtube_link'
        ]));

        // ✅ Assign the logged-in user ID automatically
        $product->user_id = $request->user()->id;

        foreach (['image', 'image_1', 'image_2', 'image_3'] as $imgField) {
            if ($request->hasFile($imgField)) {
                $file = $request->file($imgField);
                $filename = time() . '_' . $imgField . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/products'), $filename);
                $product->$imgField = 'uploads/products/' . $filename;
            }
        }

        $product->save();

        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
    }

    // API: rate product
    public function rateProduct(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'product_id' => 'required|exists:products,id',
                'rating' => 'required|integer|min:1|max:5',
            ]);

            Rating::updateOrCreate(
                ['user_id' => $request->user_id, 'product_id' => $request->product_id],
                ['rating' => $request->rating]
            );

            return response()->json(['message' => 'Rating saved']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save rating',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // API: products with average ratings
    public function productsWithRatings()
    {
        try {
            $products = Product::with('ratings')->get()->map(function ($product) {
                $product->average_rating = round($product->average_rating, 1);
                return $product;
            });

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch products with ratings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // API: toggle favorite
    public function toggleFavorite(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'product_id' => 'required|exists:products,id',
            ]);

            $fav = Favorite::where('user_id', $request->user_id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($fav) {
                $fav->delete();
                return response()->json(['message' => 'Removed from favorites']);
            }

            $favorite = Favorite::create([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
            ]);

            return response()->json(['message' => 'Added to favorites', 'favorite' => $favorite], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to toggle favorite',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // API: list favorites
    public function listFavorites(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $favorites = Favorite::where('user_id', $request->user_id)->with('product')->get();

            return response()->json([
                'favorites' => $favorites->pluck('product_id'),
                'data' => $favorites
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch favorites',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function myproduct(Request $request)
    {
        $user = $request->user();
        $products = Product::where('user_id', $user->id)->get();
        return response()->json($products);
    }
    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);
    
        if ($product->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
    public function update(Request $request, $id)
    {
        $product = Product::where('user_id', $request->user()->id)->findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'color' => 'nullable|string',
            'youtube_link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        $product->fill($request->only([
            'name', 'description', 'price', 'stock', 'color', 'youtube_link'
        ]));
    
        foreach (['image', 'image_1', 'image_2', 'image_3'] as $imgField) {
            if ($request->hasFile($imgField)) {
                if ($product->$imgField && file_exists(public_path($product->$imgField))) {
                    unlink(public_path($product->$imgField));
                }
    
                $file = $request->file($imgField);
                $filename = time() . '_' . $imgField . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/products'), $filename);
                $product->$imgField = 'uploads/products/' . $filename;
            }
        }
    
        $product->save();
    
        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ], 200);
    }
    
    
}
