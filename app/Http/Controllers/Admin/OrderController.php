<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Transaction::with(['user', 'event', 'ticketType'])->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }
}
