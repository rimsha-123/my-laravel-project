<?php
namespace App\Http\Controllers;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Auth;


class AdminController extends Controller
{
   
    
    public function login(){
        return view('admin.login');
    }

    public function loginPost(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Get the admin by email
        $admin = Admin::where('email', $request->email)->first();
    
        if ($admin && $admin->password === $request->password) {
            // Manually log in the admin
            Auth::guard('admin')->login($admin);
    
            // Redirect to dashboard
            return redirect()->intended('admin/dashboard');
        }
    
        // If authentication fails, return with error message
        return redirect('admin/login')->withErrors(['email' => 'Invalid credentials.']);
    }
  

    public function dashboard(){
        return view('admin.dashboard');
    }

    public function Admin()
    {
        $admins = Admin::all(); // Fetch all admins from the database
        return view('admin.Admin', compact('admins'));
    }
    
    
    public function edit($id)
    {
        $admins = Admin::findOrFail($id); // Fetch a single admin by ID
        return view('admin.edit', compact('admins')); // Pass the individual admin to the view
    }
    

    public function store(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email|unique:admins,email',
        'password' => 'required', // Ensure confirmation matches
    ]);

    // Create a new admin record in the database
    $admin = new Admin();
    $admin->firstname = $request->input('firstname');
    $admin->lastname = $request->input('lastname');
    $admin->email = $request->input('email');
    $admin->password = $request->input('password'); // Hash password before saving
    $admin->confirm_password = $request->input('confirm_password'); // Hash confirm password
    $admin->save();

    // Redirect with success message
    return redirect()->route('admin.Admin')->with('success', 'Admin added successfully!');
}

public function getProducts()
{
    // Yahan tum database se product list ya koi bhi admin ka data return kar sakti ho
    $products = \App\Models\Product::all();

    return response()->json($products);
}

}