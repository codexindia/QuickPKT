<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OperatorList;

class OperatorManager extends Controller
{
    public function get_operators(Request $request)
    {
        $data = OperatorList::where('status', 'active')->where('type', $request->type)->get(['name','id','short_code']);
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Retrive Successfully',
        ]);
    }
}
