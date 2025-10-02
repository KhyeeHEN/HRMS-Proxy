<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\MultiSheetAssetImport;
use Maatwebsite\Excel\Facades\Excel;

class AssetUploadController extends Controller
{
    public function showUpload()
    {
        return view('assets.uploadasset');
    }

    public function uploadAsset(Request $request)
    {
        // Validate uploaded file
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,csv',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Process file import
        try {
            $import = new MultiSheetAssetImport();
            $import->import($request->file('file'));

            return redirect()->back()->with('success', 'Asset file imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import asset file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/Asset Data.xlsx');

        if (!file_exists($filePath)) {
            return abort(404, 'Template file not found.');
        }

        return response()->download($filePath);
    }
}
