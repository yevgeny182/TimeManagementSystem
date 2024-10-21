<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Ensure you import the User model
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UserController extends Controller
{
    public function store(Request $request) {
        // Validate the incoming request
        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:15',
        ]);

        // Create the user
        User::create([
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'User added successfully!');
    }

    public function index(){
        $users = User::all()->map(function ($user) {
            
            return $user;
        });
        return view('index', compact('users'));
    }
    
    public function login(Request $request) {
        $user = User::find($request->user_id);
        $user->login_time = now()->setTimezone('Asia/Singapore'); // Set timezone
        $user->save();
    
        $request->session()->put('user_' . $user->id, true); // Store login state
        return response()->json(['status' => 'success', 'login_time' => $user->login_time->format('Y-m-d H:i:s')]); // Return formatted time
    }
    
    

        public function logout(Request $request) {
            $user = User::find($request->user_id);
            $user->logout_time = now()->setTimezone('Asia/Singapore'); // Set timezone
            $user->save();
        
            $formattedLogoutTime = $user->logout_time->format('Y-m-d H:i:s'); // Customize format as needed
        
            $userId = $request->input('user_id');
            $request->session()->forget('user_' . $userId); // Clear login state
            return response()->json(['status' => 'success', 'logout_time' => $formattedLogoutTime]);
        }

            public function destroy($id){
                    $user = User::find($id);
                    if ($user) {
                        $user->delete();
                        return response()->json(['status' => 'success', 'message' => 'User deleted successfully.'], 200);
                    }
                    return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
                }

    
    
}
