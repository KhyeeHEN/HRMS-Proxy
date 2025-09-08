<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    protected $table = 'asset_categories';

    protected $fillable = ['name'];

    // Relationship: One category has many assets
    public function assets()
    {
        return $this->hasMany(Asset::class, 'type');
    }
    public function edit($id)
    {
        $asset = Asset::with(['currentAssignment', 'category'])->findOrFail($id);
        $employees = Employee::all();
        $departments = Department::all();
        $categories = AssetCategory::all(); // Add this line

        return view('assets.edit', compact('asset', 'employees', 'departments', 'categories'));
    }
}