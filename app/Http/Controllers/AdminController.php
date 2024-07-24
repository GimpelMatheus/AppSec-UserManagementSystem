<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.dashboard', compact('users'));
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.dashboard')->with('status', 'User deleted!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Admin created!');
    }

    public function unlockAccount($id)
    {
        $user = User::findOrFail($id);
        $user->failed_login_attempts = 0;
        $user->account_locked = false;
        $user->save();

        return redirect()->route('admin.dashboard')->with('status', 'Account unlocked!');
    }

}

