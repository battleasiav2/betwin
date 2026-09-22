<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Promotions';
        $promotions = Promotion::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.promotions.index', compact('pageTitle', 'promotions'));
    }

    public function create()
    {
        $pageTitle = 'Add New Promotion';
        return view('admin.promotions.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required',
            'image'               => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'link'                => 'nullable|string',
            'bonus_percent'       => 'required|numeric|min:0',
            'turnover_multiplier' => 'required|numeric|min:0',
            'min_limit'           => 'required|numeric|min:0',
            'max_bonus'           => 'required|numeric|min:0',
            'is_link'             => 'required|in:0,1',
            'show_deposit'        => 'required|in:0,1',
        ]);

        $promotion = new Promotion();
        $promotion->title = $request->title;
        $promotion->description = $request->description;
        $promotion->link = $request->link;
        $promotion->bonus_percent = $request->bonus_percent;
        $promotion->turnover_multiplier = $request->turnover_multiplier;
        $promotion->min_limit = $request->min_limit;
        $promotion->max_bonus = $request->max_bonus;
        $promotion->is_link = $request->is_link;
        $promotion->show_deposit = $request->show_deposit;

        if ($request->hasFile('image')) {
            try {
                $promotion->image = fileUploader($request->image, getFilePath('promotion'), getFileSize('promotion'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $promotion->save();
        $notify[] = ['success', 'Promotion saved successfully'];
        return to_route('admin.promotion.index')->withNotify($notify);
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $pageTitle = 'Edit Promotion - ' . $promotion->title;
        return view('admin.promotions.edit', compact('pageTitle', 'promotion'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required',
            'image'               => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'link'                => 'nullable|string',
            'bonus_percent'       => 'required|numeric|min:0',
            'turnover_multiplier' => 'required|numeric|min:0',
            'min_limit'           => 'required|numeric|min:0',
            'max_bonus'           => 'required|numeric|min:0',
            'is_link'             => 'required|in:0,1',
            'show_deposit'        => 'required|in:0,1',
        ]);

        $promotion = Promotion::findOrFail($id);
        $promotion->title = $request->title;
        $promotion->description = $request->description;
        $promotion->link = $request->link;
        $promotion->bonus_percent = $request->bonus_percent;
        $promotion->turnover_multiplier = $request->turnover_multiplier;
        $promotion->min_limit = $request->min_limit;
        $promotion->max_bonus = $request->max_bonus;
        $promotion->is_link = $request->is_link;
        $promotion->show_deposit = $request->show_deposit;

        if ($request->hasFile('image')) {
            try {
                $old = $promotion->image;
                $promotion->image = fileUploader($request->image, getFilePath('promotion'), getFileSize('promotion'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $promotion->save();
        $notify[] = ['success', 'Promotion updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->status = $promotion->status == 1 ? 0 : 1;
        $promotion->save();
        $notify[] = ['success', 'Status changed successfully'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $promotion = Promotion::findOrFail($id);
        fileManager()->removeFile(getFilePath('promotion') . '/' . $promotion->image);
        $promotion->delete();
        $notify[] = ['success', 'Promotion deleted successfully'];
        return back()->withNotify($notify);
    }
}