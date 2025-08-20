<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController ;
use App\Http\Controllers\UserController ;
use App\Http\Controllers\ProductController ;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\SellerRequestController;

// use App\Http\Controllers\admin\CategoryController;
Route::get('/', function () {
    return view('welcome');
});


Route::get('admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'loginPost'])->name('admin.login.post');
Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->middleware('auth:admin')->name('admin.dashboard');
Route::get('admin/Admin', [AdminController::class, 'Admin'])->name('admin.Admin');
Route::get('admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
Route::post('admin/store', [AdminController::class, 'store'])->name('admin.store');

Route::get('admin/users', [UserController::class, 'index'])->name('admin.users');

Route::get('admin/products', [ProductController::class, 'show'])->name('admin.products');

Route::post('admin/products/store', [ProductController::class, 'store'])->name('admin.products.store');


Route::get('admin/category', [CategoryController::class, 'showForm'])->name('category.form');
Route::post('admin/category', [CategoryController::class, 'store'])->name('category.store');

Route::get('admin/subcategory', [SubcategoryController::class, 'showForm'])->name('subcategory.form');
Route::post('admin/subcategory', [SubcategoryController::class, 'store'])->name('subcategory.store');

Route::get('admin/sellerrequests', [SellerRequestController::class, 'index'])->name('admin.sellerrequests');
Route::post('/admin/sellerrequests/{id}/approve', [SellerRequestController::class, 'approve'])->name('sellerrequests.approve');
Route::post('/admin/sellerrequests/{id}/reject', [SellerRequestController::class, 'reject'])->name('sellerrequests.reject');
