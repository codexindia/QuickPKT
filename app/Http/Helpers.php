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
?>