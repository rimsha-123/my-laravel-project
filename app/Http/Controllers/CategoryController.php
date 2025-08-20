<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::with('subcategories')->get();
    }
    
    public function showForm()
    {
        return view('admin.category'); // no folder, direct file
    }

    public function store(Request $request)
    {
        Category::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Category added!');
    }
}
