<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Models\Category;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the types.
     */
    public function index(Request $request)
    {
        $query = Type::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'oldest') {
                $query->oldest('id');
            } else {
                $query->latest('id');
            }
        } else {
            $query->latest();
        }

        $types = $query->paginate(5)->appends($request->except('page'));
        $categories = Category::where('status', 'active')->get();
        return view('admin.types.index', compact('types', 'categories'));
    }

    /**
     * Store a newly created type in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'status'      => 'required|in:active,inactive',
        ]);

        Type::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.types.index')->with('success', 'Type created successfully.');
    }

    /**
     * Update the specified type in storage.
     */
    public function update(Request $request, Type $type)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'status'      => 'required|in:active,inactive',
        ]);

        $type->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.types.index')->with('success', 'Type updated successfully.');
    }

    /**
     * Remove the specified type from storage.
     */
    public function destroy(Type $type)
    {
        $type->delete();
        return redirect()->route('admin.types.index')->with('success', 'Type deleted successfully.');
    }
}
