<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;

class SubcategoryController extends Controller
{
    public function getByCategory($id)
{
    $subcategories = Subcategory::where('category_id', $id)->get();
    return response()->json($subcategories);
}

    public function showForm()
    {
        $categories = Category::all();
        return view('admin.subcategory', compact('categories'));
    }

    public function store(Request $request)
    {
        Subcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id
        ]);
        return redirect()->back()->with('success', 'Subcategory added!');
    }
    public function index()
{
    return response()->json(Subcategory::all());
}

}
