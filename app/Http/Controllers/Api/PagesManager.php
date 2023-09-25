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
    public function terms_and_conditions(){
        $core = SystemPages::where('page_title','terms_and_conditions')->first();
        return response()->json([
            'status' => true,
            'data' => $core->page_content,
            'message' => 'terms_and_conditions'
        ]);
    }
}
