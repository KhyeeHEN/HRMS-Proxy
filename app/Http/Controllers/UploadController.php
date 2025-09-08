<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\MultiSheetEmployeeImport;
use App\Imports\MultiSheetFamilyImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    public function showEmp()
    {
        return view('uploademp');
    }

    public function showFam()
    {
        return view('uploadfam');
    }
    public function uploadEmployee(Request $request)
    {
        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,csv',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle file import
        $file = $request->file('file');
        try {
            $import = new MultiSheetEmployeeImport;
            $import->import($file);

            return redirect()->back()->with('success', 'File imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import file: ' . $e->getMessage());
        }
    }

    public function uploadFamily(Request $request)
    {
        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,csv',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle file import
        $file = $request->file('file');
        try {
            $import = new MultiSheetFamilyImport;
            $import->import($file);

            return redirect()->back()->with('success', 'File imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import file: ' . $e->getMessage());
        }
    }
}
