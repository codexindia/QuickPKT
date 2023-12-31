<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UserAlert;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
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
            'profile_pic' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ], [
            'first_name.required' => 'Please Enter Your first name',
            'last_name.required' => 'Please Enter Your Last name',
            'email.email' => 'Please Enter Valid Email',
            'profile_pic.image' => 'Upload your Valid profile picture'
        ]);
        $updated_filed = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,

        ];
        if ($request->hasFile('profile_pic')) {
            $image_path = Storage::put('public/users/profiles', $request->file('profile_pic'));
            $updated_filed['profile_pic'] = $image_path;
        }
        if ($request->has('email')) {
            $updated_filed['email'] = $request->email;
        }
        $user = User::find($this->user_id);
        $user->update($updated_filed);
        $param['title'] = 'lorem ipsum dolor sit amet, consectet';
        $param['subtitle'] = 'lorem ipsum dolor sit amet, consectet lorem ipsum dolor sit amet, consectet lorem ipsum dolor sit amet, consectet';
        Notification::send($user, new UserAlert($param));
        return response()->json([
            'status' => true,
            'message' => 'User Updated SuccessFully',
        ]);
    }
   
}
