<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;    
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::orderBy('sequence', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'newest') {
                $query->latest('id');
            } else {
                $query->oldest('id');
            }
        } else {
            $query->orderBy('sequence', 'asc');
        }

        $banners = $query->paginate(5)->appends($request->except('page'));
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image'=> 'required|image|mimes:jpeg,png,jpg|max:2048',
            'link'=> 'nullable|string',
            'sequence' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
            'expires_at' => 'nullable|date',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('banners','public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success','Banner Created Successfully');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // အဟောင်းရှိပြီးသားမို့ မပါလည်းရသည်
            'link' => 'nullable|string',
            'sequence' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
            'expires_at' => 'nullable|date',
        ]);

        $data = $request->except('image');

        // ပုံအသစ်ပါလာပါက အဟောင်းကိုဖျက်ပြီး အသစ်သိမ်းခြင်း
        if ($request->hasFile('image')) {
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    // Banner ဖျက်ရန်
    public function destroy(Banner $banner)
    {
        // Database မှ မဖျက်မီ Server ပေါ်ရှိ ပုံဖိုင်ကို အရင်ဖျက်ပါမည်
        if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }
        
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
    
}
