<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport; // Import your import class
use App\Models\User; // Assuming you have a User model

class UserImportController extends Controller
{
    public function import(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        // Import the Excel file
        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Users imported successfully.');
    }
}
