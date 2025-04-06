<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class transactionsController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all()->where('user_id', auth()->user()->id);

        return Inertia::render('order/Index' , [
            'transactions' => $transactions,

        ]);
    }
}
