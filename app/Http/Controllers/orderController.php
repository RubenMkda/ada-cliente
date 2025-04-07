<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class orderController extends Controller
{
    public function index()
    {
        $orders = Order::all()->where('user_id', auth()->user()->id);

        return Inertia::render('order/Index' , [
            'orders' => $orders,

        ]);
    }
}
