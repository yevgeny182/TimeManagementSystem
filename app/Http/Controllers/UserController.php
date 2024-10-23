<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Ensure you import the User model
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;

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

    public function index(Request $request){
        $users = User::all();

        // Count the total number of users
        $totalUsers = $users->count();

        // Count logged-in users based on session
        $loggedUsersCount = 0;
        foreach ($users as $user) {
            if ($request->session()->has('user_' . $user->id)) {
                $loggedUsersCount++;
            }
        }

        return view('index', compact('users', 'totalUsers', 'loggedUsersCount'));
    }

    public function login(Request $request) {
        $user = User::find($request->user_id);
        $user->login_time = now()->setTimezone('Asia/Singapore'); // Set timezone
        $user->save();

        $request->session()->put('user_' . $user->id, true); // Store login state
        return response()->json(['status' => 'success', 'login_time' => $user->login_time->format('Y-m-d H:i:s')]); // Return formatted time
    }

    public function logout(Request $request){
    $user = User::find($request->user_id);
    if (!$user) {
        return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
    }
    $user->logout_time = now()->setTimezone('Asia/Singapore');
    $user->save();
    // Format the logout time
    $formattedLogoutTime = $user->logout_time->format('Y-m-d H:i:s');
    // Calculate the time logged
    if ($user->login_time && $user->logout_time) {
        $timeIn = \Carbon\Carbon::parse($user->login_time);
        $timeOut = \Carbon\Carbon::parse($user->logout_time);
        $diff = $timeIn->diff($timeOut);
        $timeLogged = "{$diff->h} hour(s) {$diff->i} minutes";
    } else {
        $timeLogged = "N/A";
    }

    $request->session()->forget('user_' . $request->user_id);

    // Return both logout time and time logged
    return response()->json([
        'status' => 'success',
        'logout_time' => $formattedLogoutTime,
        'time_logged' => $timeLogged,
    ]);
}

public function destroy($id, Request $request){
    $user = User::find($id);
    if ($user) {
        // Decrement total users
        $totalUsers = User::count() - 1;
        // Get the current logged-in users count from the session
        $loggedUsersCount = session()->get('loggedUsersCount', 0);
        // Check if the deleted user is currently logged in based on session
        if ($request->session()->has('user_' . $user->id)) {
            // Decrement the logged-in users count if the user was logged in
            $loggedUsersCount -= 1;
        }
        // Delete the user
        $user->delete();
        // Update the session with the new logged users count
        session()->put('loggedUsersCount', $loggedUsersCount);

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully.',
            'totalUsers' => $totalUsers,
            'loggedUsersCount' => $loggedUsersCount
        ], 200);
    }

    return response()->json(['status' => 'error', 'message' => 'User not found.'], 404);
}



    public function importUsers(Request $request) {
        $request->validate([
            'import_file' => [
                'required',
                'file'
            ],
        ]);

        Excel::import(new UsersImport, $request->file('import_file'));
        return redirect()->back()->with('success', 'Users imported successfully!');
    }

   

    public function getTimeLogged($userId) {
        $user = User::find($userId);
        Log::info("User ID: $userId - Login Time: {$user->login_time}, Logout Time: {$user->logout_time}");
        if ($user && $user->login_time && $user->logout_time) {
            $timeIn = \Carbon\Carbon::parse($user->login_time);
            $timeOut = \Carbon\Carbon::parse($user->logout_time);
            $diff = $timeIn->diff($timeOut);
            $timeLogged = "{$diff->h} hour(s) {$diff->i} minutes";
    
            return response()->json(['time_logged' => $timeLogged]);
        }
        return response()->json(['time_logged' => 'N/A']);
    }

   /*  public function assignHours(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'hours' => 'required|numeric|min:0', // Adjust as necessary
        ]);

            $user = User::find($request->user_id);
            $user->time_remaining = $request->hours . ' hours'; // Store hours in a user-friendly format
            $user->save();

            return response()->json(['success' => true]);
        } */

        public function assignHours(Request $request) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'hours' => 'required|numeric|min:0', // Ensure hours is a valid number
            ]);
        
            $user = User::find($request->user_id);
            
            // Convert hours to HH:MM:SS format
            $hours = (int)$request->hours; // Convert input to integer
            $timeRemaining = sprintf('%02d:00:00', $hours); // Format as HH:MM:SS
        
            $user->time_remaining = $timeRemaining; // Store time in HH:MM:SS format
            $user->save();
        
            return response()->json(['success' => true]);
        }

                   

}
