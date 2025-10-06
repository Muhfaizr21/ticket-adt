<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        // Ambil user yang pernah membeli tiket (punya order)
        $customers = User::whereHas('orders')->withCount('orders')->get();
        return view('admin.customers.index', compact('customers'));
    }
}
