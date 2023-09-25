<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemPages;
class PagesManager extends Controller
{
    public function privacy_policy(Request $request){
        $core = SystemPages::where('page_title','privacy_policy')->first();
        return response()->json([
            'status' => true,
            'data' => $core->page_content,
            'message' => 'privacy_policy'
        ]);
    }
}
