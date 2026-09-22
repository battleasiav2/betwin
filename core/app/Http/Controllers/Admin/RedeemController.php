<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RedeemController extends Controller {
    public function index() {
        $pageTitle = "All Redeem Codes";
        $redeems = DB::table('redeem_codes')->orderBy('id','desc')->get();
        return view('admin.redeem.index', compact('pageTitle', 'redeems'));
    }

    public function store(Request $request) {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'user_limit' => 'required|integer|gt:0',
            'user_redeem_limit' => 'required',
        ]);

        $code = strtoupper(substr(md5(uniqid()), 0, 6));

        DB::table('redeem_codes')->insert([
            'code' => $code,
            'type' => $request->type, // 1 for Fixed, 2 for Percent
            'amount' => $request->amount,
            'user_limit' => $request->user_limit,
            'user_redeem_limit' => $request->user_redeem_limit == 'unlimited' ? 999999 : $request->user_redeem_limit,
            'status' => 1
        ]);

        $notify[] = ['success', 'Redeem code generated: ' . $code];
        return back()->withNotify($notify);
    }

    public function status($id) {
        $redeem = DB::table('redeem_codes')->where('id', $id)->first();
        $status = $redeem->status == 1 ? 0 : 1;
        DB::table('redeem_codes')->where('id', $id)->update(['status' => $status]);
        $notify[] = ['success', 'Status updated successfully'];
        return back()->withNotify($notify);
    }
}