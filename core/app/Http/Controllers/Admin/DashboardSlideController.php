<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DashboardSlide;
use Illuminate\Http\Request;

class DashboardSlideController extends Controller
{
    public function index()
    {
        $pageTitle = 'Manage Dashboard Slides';
        $slides = DashboardSlide::latest()->get();
        return view('admin.dashboard_slide.index', compact('pageTitle', 'slides'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|url',
        ]);

        $slide = new DashboardSlide();
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $location = 'assets/images/slides';
            $image->move($location, $filename);
            $slide->image = $filename;
        }

        $slide->url = $request->url ?? '#';
        $slide->save();

        $notify[] = ['success', 'Slide added successfully'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $slide = DashboardSlide::findOrFail($id);
        $path = 'assets/images/slides/' . $slide->image;
        if (file_exists($path)) {
            @unlink($path);
        }
        $slide->delete();
        
        $notify[] = ['success', 'Slide deleted successfully'];
        return back()->withNotify($notify);
    }
}