<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Facades\CauserResolver;

class UserManager extends Controller
{
    private $user_id = null;
    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            CauserResolver::setCauser($request->user());
           
            $this->user_id = $request->user()->id;
            return $next($request);
        });
    }
    public function get_current_user(Request $request)
    {
        $filled_required = false;
        if ($request->user()->first_name == null && $request->user()->last_name == null)
            $filled_required = true;

        return response()->json([
            'status' => true,
            'data' => $request->user(),
            'filled_required' => $filled_required,
            'message' => 'User Retrived',
        ]);
    }
    public function update_user(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email',
        ]);
        $updated_filed = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ];
        if ($request->has('email')) {
            $updated_filed = [
                'email' => $request->email
            ];
        }
        User::find($this->user_id)->update($updated_filed);
        return response()->json([
            'status' => true,
            'message' => 'User Updated SuccessFully',
        ]);
    }
}
