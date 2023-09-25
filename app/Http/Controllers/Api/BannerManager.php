<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppBanners;

class BannerManager extends Controller
{
    public function get_banners(Request $request)
    {
        if ($request->type == 'main') {
            $all = AppBanners::where('type', 'main')->orderBy('id','desc')->get();
        } elseif ($request->type == 'featured') {
            $all = AppBanners::where('type', 'featured')->orderBy('id','desc')->get();
        } elseif ($request->type == 'spotlight') {
            $all = AppBanners::where('type', 'spotlight')->orderBy('id','desc')->get();
        }
        if(isset($all))
        {
            return response()->json([
                'status' => true,
                'data' => $all,
                'message' => 'Banners Retrieved Success',
            ]);
        }
    }
}
