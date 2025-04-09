<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class transactionsController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->user()->id)->get();

        return Inertia::render('transactions/Index' , [
            'transactions' => $transactions,

        ]);
    }
}
