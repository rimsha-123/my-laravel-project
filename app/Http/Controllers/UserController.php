<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller

{
    

    public function index()
    {
        $users = User::all(); // sab users le aao database se
        return view('admin.users', compact('users'));
    }
}
