<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiGameControlController extends Controller
{
    public function index()
    {
        $pageTitle = 'API Game Management';
        $apiGames = DB::table('api_game_controls')->get();
        return view('admin.api_game.index', compact('pageTitle', 'apiGames'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1,2,3',
        ]);

        DB::table('api_game_controls')->where('id', $id)->update([
            'status' => $request->status,
            'updated_at' => now()
        ]);

        $notify[] = ['success', 'Status updated successfully'];
        return back()->withNotify($notify);
    }
}