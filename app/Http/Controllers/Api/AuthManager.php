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
    private $logevent = null;
    public function __construct(){
        $this->logevent = activity()->event('authentication');
    }
    public function login_or_signup(Request $request)
    {
        
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'phone' => 'required|numeric'
        ]);
        if ($this->VerifyOTP($request->phone, $request->otp)) {
            $checkphone = User::where('mobile_number', $request->phone)->first();
            if ($checkphone) {
             //   $checkphone->tokens()->delete();
                $token = $checkphone->createToken('auth_token')->plainTextToken;
                activity()->causedBy($checkphone)->event('authentication')->log('Token Generated Successfully (Login) '.$request->phone.' IP: '.$request->ip());
                return response()->json([
                    'status' => true,
                    'message' => 'OTP Verified  Successfully (Login)',
                    'token' => $token,
                ]);
            } else {
                $newuser = User::create([
                    'mobile_number' => $request->phone,
                ]);

                $token = $newuser->createToken('auth_token')->plainTextToken;
                activity()->event('authentication')->log('New Token Created (new user): ' . $request->phone.' IP: '.$request->ip());
                return response()->json([
                    'status' => true,
                    'message' => 'OTP Verified  Successfully (new user)',
                    'token' => $token,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Your OTP is invalid'
            ]);
        }
    }
    private function VerifyOTP($phone, $otp)
    {
      
        $checkotp = VerficationCodes::where('phone', $phone)
            ->where('otp', $otp)->latest()->first();
        $now = Carbon::now();
        if (!$checkotp) {
            $this->logevent->log('Trying To Verify OTP Invalid OTP '.$phone);
            return 0;
        } elseif ($checkotp && $now->isAfter($checkotp->expire_at)) {
            $this->logevent->log('Trying To Verify OTP Expired OTP '.$phone);
            return 0;
        } else {
            $this->logevent->log('Success OTP Verified '.$phone);
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
        $this->logevent->log('Trying To Send OTP '.$request->phone.' IP: '.$request->ip());
        if ($this->genarateotp($request->phone)) {
            return response()->json([
                'status' => true,
                'message' => 'OTP send successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'OTP Send UnsuccessFully Or Limit Exeeded Try Again Later',
            ]);
        }
    }
    public function resend(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:10',
        ]);
        $phone = $request->phone;
        $this->logevent->log('Trying To Resend OTP '.$request->phone.' IP: '.$request->ip());
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
        $otpmodel = VerficationCodes::where('phone', $number);
       
        if ($otpmodel->count() > 10) {
            return false;
        }
        $checkotp = $otpmodel->latest()->first();
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
            if ($decode->return) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function logout(Request $request){
       
        activity()->causedBy($request->user())->event('authentication')->log('User Logout  IP: '.$request->ip());
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Logout Successfully',
        ]);
    }
}
