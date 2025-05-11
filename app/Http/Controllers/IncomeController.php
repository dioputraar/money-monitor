<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index(){
        $income = Transaction::where('type', 1)->where('user_id', Auth::user()->id)->get();
        return view('income/index', [
            'income' => $income
        ]);
    }

    public function get($id = null)
    {
        if ($id !== null) {
            $transaction = Transaction::with('category')->find($id);
        } else {
            $transaction = Transaction::with('category')->where('type', 1)->where('user_id', Auth::user()->id)->get();
        }
        // dd($transaction->toArray());
        return response()->json([
            'data' => $transaction
        ]);
    }

    public function upsert(Request $request){
        $request->validate([
            'name' => 'required',
            'total' => 'required|numeric',
            'category' => 'required|exists:category,id',
            'date' => 'required|date',
        ]);

        if($request->id){
            $transaction = Transaction::find($request->id);
            $transaction->name = $request->name;
            $transaction->description = $request->description;
            $transaction->total = $request->total;
            $transaction->category_id = $request->category;
            $transaction->date = $request->date;
            $transaction->user_id = Auth::user()->id;
            $transaction->updated_by = Auth::user()->name;
            $transaction->save();
            return response()->json([
                'message' => 'Transaction updated successfully',
                'transaction' => $transaction
            ]);
        } else {
            $transaction = Transaction::create([
                'name' => $request->name,
                'description' => $request->description,
                'total' => $request->total,
                'category_id' => $request->category,
                'date' => $request->date,
                'user_id' => Auth::user()->id,
                'type' => 1,
                'created_by' => Auth::user()->name,
                'updated_by' => Auth::user()->name,
            ]);
            return response()->json([
                'message' => 'Transaction created successfully',
                'transaction' => $transaction
            ]);
        }
    }

    public function delete($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->delete();
            return response()->json([
                'message' => 'Transaction deleted successfully'
            ]);
        } else {
            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }
    }
}
