<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User; // Ensure you import the User model

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Assuming you have a method to validate the hash
        if (! Hash::check($hash, $user->getEmailVerificationToken())) {
            throw new ValidationException('Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return Redirect::route('home')->with('status', 'Your email address is already verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return Redirect::route('home')->with('status', 'Your email address has been verified.');
    }

    /**
     * Resend the email verification link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resendVerificationEmail(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Retrieve the user by email
        $user = User::where('email', $request->input('email'))->firstOrFail();

        // Generate a new email verification token
        $token = $user->generateEmailVerificationToken();

        // Send the email verification link
        Mail::to($user->email)->send(new VerifyEmail($user, $token));

        // Redirect back with a success message
        return Redirect::back()->with('status', 'Verification link sent!');
    }
}
