<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $balance = $user->balance->amount ?? 0;

        $transactions = $user->transactions()
            ->latest()
            ->take(5)
            ->get(['id', 'amount', 'type', 'description', 'created_at']);

        return response()->json([
            'balance' => $balance,
            'transactions' => $transactions,
        ]);
    }
}
