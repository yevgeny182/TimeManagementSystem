<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport; 
use App\Models\User; 

class UserImportController extends Controller{
    public function import(Request $request){
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,csv',
        ]);
    
    try {
        Excel::import(new UsersImport, $request->file('import_file'));
        return redirect()->back()->with('success', 'Users imported successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to import users: ' . $e->getMessage());
    }

}
}