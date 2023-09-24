<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTransaction;
use Illuminate\Support\Arr;
class WalletManager extends Controller
{
   public function get_user_balance(Request $request)
   {

    return response()->json([
        'status' => true,
        'balance' => number_format($request->user()->available_balance, 2),
        'message' => 'Wallet Balance Loaded Successfully',
    ]);
   }
   public function get_all_transaction(Request $request)
   {
    $record = UserTransaction::where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->paginate(10);
   
    return response()->json([
        'status' => true,
        'data' => $record,
        'message' => 'Wallet Transaction Loaded Successfully',
    ]);
   }
}
