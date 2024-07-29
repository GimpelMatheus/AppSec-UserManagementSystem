<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Exceptions\UnauthorizedException;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();

        // Ensure $user is an instance of User
        if (!$user instanceof User) {
            throw new UnauthorizedException('You must be logged in to view your profile.');
        }

        return view('profile.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();

        // Ensure $user is an instance of User
        if (!$user instanceof User) {
            throw new UnauthorizedException('You must be logged in to edit your profile.');
        }

        return view('profile.edit');
    }

    /**
     * Update the user's profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Ensure $user is an instance of User
        if (!$user instanceof User) {
            throw new UnauthorizedException('You must be logged in to update your profile.');
        }

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update user attributes
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the user's profile from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $user = Auth::user();

        // Ensure $user is an instance of User
        if (!$user instanceof User) {
            throw new UnauthorizedException('You must be logged in to delete your profile.');
        }

        // Delete the user's profile
        $user->delete();

        // Log the user out after deletion
        Auth::logout();

        return redirect('/')->with('success', 'Profile deleted successfully.');
    }
}

