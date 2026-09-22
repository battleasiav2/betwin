<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class RedeemController extends Controller {
    public function index() {
        $pageTitle = "Redeem Gift Code";
        return view('templates.sunfyre.redeem.redeem', compact('pageTitle'));
    }

    public function submit(Request $request) {
        $request->validate(['code' => 'required']);
        $user = auth()->user();
        $redeem = DB::table('redeem_codes')->where('code', $request->code)->where('status', 1)->first();

        if (!$redeem || $redeem->used_count >= $redeem->user_limit) {
            $notify[] = ['error', 'Code is invalid or expired'];
            return back()->withNotify($notify);
        }

        $userUsed = DB::table('redeem_logs')->where('redeem_id', $redeem->id)->where('user_id', $user->id)->count();
        if ($userUsed >= $redeem->user_redeem_limit) {
            $notify[] = ['error', 'You have reached the limit for this code'];
            return back()->withNotify($notify);
        }

        $finalAmount = ($redeem->type == 2) ? ($redeem->amount / $redeem->user_limit) : $redeem->amount;

        DB::transaction(function () use ($user, $redeem, $finalAmount) {
            $user->balance += $finalAmount;
            $user->save();

            DB::table('redeem_codes')->where('id', $redeem->id)->increment('used_count');
            DB::table('redeem_logs')->insert([
                'redeem_id' => $redeem->id,
                'user_id' => $user->id,
                'amount' => $finalAmount,
                'created_at' => now()
            ]);

            $trx = new Transaction();
            $trx->user_id = $user->id;
            $trx->amount = $finalAmount;
            $trx->post_balance = $user->balance;
            $trx->trx_type = '+';
            $trx->details = 'Redeemed Code: ' . $redeem->code;
            $trx->trx = getTrx();
            $trx->save();
        });

        $formattedAmount = number_format($finalAmount, 2);
        $notify[] = ['success', 'Success! Added: ' . $formattedAmount . ' ' . $user->currency];
        return back()->withNotify($notify);
    }
}