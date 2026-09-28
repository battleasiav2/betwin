<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveMatch;
use Illuminate\Http\Request;

class LiveMatchController extends Controller
{
    public function index()
    {
        $pageTitle = 'Live Matches';
        $matches   = LiveMatch::orderBy('sort_order')->orderBy('id', 'desc')->paginate(getPaginate());

        return view('admin.matches.index', compact('pageTitle', 'matches'));
    }

    public function create()
    {
        $pageTitle = 'Add Match';
        $match     = new LiveMatch();

        return view('admin.matches.form', compact('pageTitle', 'match'));
    }

    public function store(Request $request)
    {
        $match = new LiveMatch();
        $this->saveMatch($request, $match);

        $notify[] = ['success', 'Match saved'];
        return to_route('admin.match.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $match     = LiveMatch::findOrFail($id);
        $pageTitle = 'Edit Match';

        return view('admin.matches.form', compact('pageTitle', 'match'));
    }

    public function update(Request $request, $id)
    {
        $match = LiveMatch::findOrFail($id);
        $this->saveMatch($request, $match);

        $notify[] = ['success', 'Match updated'];
        return to_route('admin.match.index')->withNotify($notify);
    }

    public function status($id)
    {
        $match         = LiveMatch::findOrFail($id);
        $match->status = $match->status ? 0 : 1;
        $match->save();

        $notify[] = ['success', 'Match status updated'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        LiveMatch::findOrFail($id)->delete();

        $notify[] = ['success', 'Match deleted'];
        return back()->withNotify($notify);
    }

    private function saveMatch(Request $request, LiveMatch $match)
    {
        $request->validate([
            'game_name'    => 'required|string|max:80',
            'title'        => 'required|string|max:160',
            'spots_filled' => 'required|integer|min:0',
            'spots_total'  => 'required|integer|min:1|gte:spots_filled',
            'entry_fee'    => 'required|numeric|min:0',
            'prize'        => 'required|numeric|min:0',
            'sort_order'   => 'nullable|integer|min:0',
        ]);

        $match->game_name    = $request->game_name;
        $match->title        = $request->title;
        $match->spots_filled = $request->spots_filled;
        $match->spots_total  = $request->spots_total;
        $match->entry_fee    = $request->entry_fee;
        $match->prize        = $request->prize;
        $match->is_live      = $request->boolean('is_live');
        $match->status       = $request->boolean('status');
        $match->sort_order   = $request->input('sort_order', 0);
        $match->save();
    }
}
