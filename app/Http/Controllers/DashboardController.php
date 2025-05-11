<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard/index');
    }

    public function get()
    {
        $data = Transaction::where('user_id', Auth::user()->id)
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->selectRaw('SUM(CASE WHEN type = 1 THEN total ELSE 0 END) as income, SUM(CASE WHEN type = 0 THEN total ELSE 0 END) as expense')
            ->first();

        $data2 = Transaction::where('user_id', Auth::user()->id)
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->join('category', 'transaction.category_id', '=', 'category.id')
            ->selectRaw('transaction.category_id, SUM(transaction.total) as total_expense, category.name as category_name')
            ->where('transaction.type', 0)
            ->groupBy('transaction.category_id', 'category.name')
            ->orderByDesc('total_expense')
            ->get();

            $data3 = Transaction::where('user_id', Auth::user()->id)
            ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(CASE WHEN type = 1 THEN total ELSE 0 END) as total_income, SUM(CASE WHEN type = 0 THEN total ELSE 0 END) as total_expense')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json([
            'data' => [
                'income' => $data->income ?? 0,
                'expense' => $data->expense ?? 0,
                'top_expense' => $data2,
                'monthly' => $data3
            ]
        ]);
    }
}
