<?php 
use App\Models\User;
use App\Models\UserTransaction;
if (!function_exists('wallet_debit')) {
    function wallet_debit($user_id, $amount, $description, $status = 'success')
    {
        $user = User::find($user_id);
        if ($user->available_balance >= $amount) {

            $new = new UserTransaction;
            $new->user_id = $user_id;
            $new->amount = $amount;
            $new->reference = 'QKTXN' . rand(10000, 99999) . rand(10000, 99999);
            $new->closing_balance = $user->available_balance - $amount;
            $new->opening_balance = $user->available_balance;
            $new->description = $description;
            $new->status = $status;
            $new->type = 'debit';
            if ($user->decrement('available_balance', $amount)) {
                $new->save();
                return true;
            }
        } else {
            return false;
        }
    }
}
if (!function_exists('wallet_credit')) {
    function wallet_credit($user_id, $amount, $description, $status = 'success')
    {
        $user = User::find($user_id);


        $new = new UserTransaction;
        $new->user_id = $user_id;
        $new->amount = $amount;
        $new->reference = 'QKTXN' . rand(10000, 99999) . rand(10000, 99999);
        $new->closing_balance = $user->available_balance + $amount;
        $new->opening_balance = $user->available_balance;
        $new->description = $description;
        $new->status = $status;
        $new->type = 'credit';
        if ($user->increment('available_balance', $amount)) {
            $new->save();
            return true;
        } else {
            return false;
        }
    }
}
if (! function_exists('send2client')) {
    function send2client($string, $action = 'e')
    {
        $string = json_encode($string);
        $secret_key = 'YourSecretKey';
        $secret_iv = 'YourSecretIv';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') { // default, encrypt
          
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') { // decrypt
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
       
        }

        return response()->json(['response' => $output]);
    }
}

?>