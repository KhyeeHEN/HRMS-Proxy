<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use Illuminate\Http\Request;

class AssetCategoryController extends Controller
{
    public function index()
    {
        $categories = AssetCategory::all();
        return view('assets.category', compact('categories'));
    }
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:asset_categories,name',
        ]);

        // Create new category
        AssetCategory::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->back()->with('success', 'Category added successfully.');
    }

    public function show($id)
    {
        $category = AssetCategory::findOrFail($id);
        return view('assets.view_category', compact('category'));
    }

    public function edit($id)
    {
        $category = AssetCategory::findOrFail($id);
        return view('assets.edit_category', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:asset_categories,name,' . $id,
        ]);

        $category = AssetCategory::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('asset-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = AssetCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('asset-categories.index')->with('success', 'Category deleted successfully.');
    }
}