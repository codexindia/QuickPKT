<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerficationCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class AuthManager extends Controller
{
    public function login_or_signup(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'phone' => 'required|numeric'
        ]);
        if ($this->VerifyOTP($request->phone, $request->otp)) {
            $checkphone = User::where('mobile_number', $request->phone)->first();
            if ($checkphone) {

                $token = $checkphone->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'status' => true,
                    'message' => 'OTP Verified  Successfully (Login)',
                    'token' => $token,
                ]);
            } else {
                $newuser = User::create([
                    'mobile_number' => $request->phone,
                ]);
                // $newuser->UserExtra()->create([
                //     'user_id' => $newuser->id,
                // ]);

                $token = $newuser->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'status' => true,
                    'message' => 'OTP Verified  Successfully (new user)',
                    'token' => $token,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Your OTP Is Invalid'
            ]);
        }
    }
    private function VerifyOTP($phone, $otp)
    {
        $checkotp = VerficationCodes::where('phone', $phone)
            ->where('otp', $otp)->latest()->first();
        $now = Carbon::now();
        if (!$checkotp) {
            return 0;
        } elseif ($checkotp && $now->isAfter($checkotp->expire_at)) {
            return 0;
        } else {
            $device = 'Auth_Token';
               VerficationCodes::where('phone', $phone)->delete();
            return 1;
        }
    }
    public function SendOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:10',
        ]);
       if($this->genarateotp($request->phone)){
        return response()->json([
            'status' => true,
            'message' => 'OTP send successfully',
        ]);
    }else{
        return response()->json([
            'status' => false,
            'message' => 'OTP Send UnsuccessFully',
        ]);
    }
    }
    public function resend(Request $request)
    {

        $phone = $request->user()->phone;

        if ($this->genarateotp($phone)) {
            return response()->json([
                'status' => true,
                'message' => 'Sms Sent Successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Sms Could Not Be Sent',
            ]);
        }
    }
    private function genarateotp($number)
    {

        $checkotp = VerficationCodes::where('phone', $number)->latest()->first();
        $now = Carbon::now();
        if ($checkotp && $now->isBefore($checkotp->expire_at)) {
            $otp = $checkotp->otp;
        } else {
            $otp = rand('100000', '999999');
            VerficationCodes::create([
                'phone' => $number,
                'otp' => $otp,
                'expire_at' => Carbon::now()->addMinute(10)
            ]);
        }



        try {
            $response = Http::withHeaders([
                'authorization' => env('FAST2SMS'),
                'accept' => '*/*',
                'cache-control' => 'no-cache',
                'content-type' => 'application/json'
            ])->post('https://www.fast2sms.com/dev/bulkV2', [
                "variables_values" => $otp,
                "route" => "dlt",
                "sender_id" => "QKPCKT",
                "message" => "159560",
                "numbers" => $number,
            ]);
            $decode = json_decode($response);
            if($decode->return)
            {
                return true;
            }else{
                return false;
            }
           
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
