<?php

namespace App\Http\Controllers\Api\Recharge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MobileRecharge extends Controller
{
    public function get_offers(Request $request)
    {
        //only for oxnpay
        $opcode = collect([
            ['short_code' => 'airtel', 'opcode' => '1'],
            ['short_code' => 'bsnl_topup', 'opcode' => '2'],
            ['short_code' => 'bsnl_special', 'opcode' => '3'],
            ['short_code' => 'jio', 'opcode' => '4'],
            ['short_code' => 'vi', 'opcode' => '5']
        ]);
        $opcode = $opcode->where('short_code', $request->operator_short_code)->first();
        if ($opcode != null) {
            $result = Http::get('https://api.oxnpay.in/Recharge/onewayy.php?key=' . env('OXNPAY') . '&op=' . $opcode['opcode']);
         
            if ($result) {
                $result = collect(json_decode($result));
                $result = $result->map(function ($tag) {
                    return [
                        'amount' => $tag->Amount,
                        'validity' => $tag->Validity,
                        'planid' => $tag->Planid,
                        'description' => $tag->Description,
                    ];
                })
                    ->toArray();

                if ($result != null)
                    return response()->json([
                        'status' => true,
                        'data' => $result,
                        'message' => 'Operator Offers Received',
                    ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Operator'
                ]);
            }
        }
    }
}
