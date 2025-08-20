<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\SellerRequestController;
use App\Http\Controllers\OrderController;

// --------------------
// Public Routes
// --------------------
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me',           [AuthController::class, 'me']);
    Route::post('/logout',      [AuthController::class, 'logout']);
    Route::post('/logout-all',  [AuthController::class, 'logoutAll']);
});

Route::middleware('auth:sanctum')->get('/profile', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'user' => $request->user()
    ]);
});



Route::get('/categories', [CategoryController::class, 'index']); 
Route::get('/categories/{id}/subcategories', [SubcategoryController::class, 'getByCategory']);
Route::get('/subcategories', [SubcategoryController::class, 'index']);

// --------------------
// Products
// --------------------
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products/storesimple', [ProductController::class, 'storeSimple']);
Route::post('/products/rateproduct', [ProductController::class, 'rateProduct']);
Route::get('/products/withratings', [ProductController::class, 'productsWithRatings']);
Route::post('/products/togglefavorite', [ProductController::class, 'toggleFavorite']);
Route::get('/products/favorites', [ProductController::class, 'listFavorites']);

// --------------------
// Seller
// --------------------
// Route::post('/becomeseller', [SellerRequestController::class, 'store']);

// // --------------------
// // Admin routes
// // --------------------
// Route::get('/sellerrequests/pending', [SellerRequestController::class, 'indexPending']);
// Route::post('/sellerrequests/{id}/approve', [SellerRequestController::class, 'approve']);
// Route::post('/sellerrequests/{id}/reject', [SellerRequestController::class, 'reject']);

// // --------------------
// // Seller allowed categories
// // --------------------
// Route::get('/seller-allowed-categories/{user_id}', [SellerRequestController::class, 'getAllowedCategoriesByUser']);


// Seller (User) routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/becomeseller', [SellerRequestController::class, 'store']);
    Route::get('/seller-allowed-categories', [SellerRequestController::class, 'getAllowedCategoriesByUser']);
    Route::post('/products/storesimple', [ProductController::class, 'storeSimple']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/sellerrequests/pending', [SellerRequestController::class, 'indexPending']);
    Route::post('/sellerrequests/{id}/approve', [SellerRequestController::class, 'approve']);
    Route::post('/sellerrequests/{id}/reject', [SellerRequestController::class, 'reject']);
});

// routes/api.php

// Route::middleware('auth:sanctum')->get('/my-products', [ProductController::class, 'myproduct']);
// Route::middleware('auth:sanctum')->delete('/products/{id}', [ProductController::class, 'destroy']);

// routes/api.php

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/my-products', [ProductController::class, 'myproduct']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
});

// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']); // place order
    // Route::get('/orders', [OrderController::class, 'index']); // list user orders
});
Route::middleware('auth:sanctum')->get('/myorders', [OrderController::class, 'myOrders']);
// routes/api.php
Route::middleware('auth:sanctum')->get('/sellerorders', [OrderController::class, 'sellerOrders']);




