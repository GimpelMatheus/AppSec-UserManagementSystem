<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at', // Ensure this column is present in your database
        'email_verification_token', // Column for storing verification token if used
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Determine if the user's email is verified.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

        /**
     * Determine if the user's email is verified.
     *
     * @return bool
     */
    public function isVerified()
    {
        // Assuming you have a 'email_verified_at' column
        return !is_null($this->email_verified_at);
    }


    /**
     * Mark the user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        $this->email_verified_at = now();
        return $this->save();
    }

    /**
     * Generate a new email verification token.
     *
     * @return string
     */
    public function generateEmailVerificationToken()
    {
        $this->email_verification_token = Str::random(60);
        $this->save();

        return $this->email_verification_token;
    }

    /**
     * Verify the given email verification token.
     *
     * @param  string  $token
     * @return bool
     */
    public function verifyEmailToken($token)
    {
        return Hash::check($token, $this->email_verification_token);
    }

    /**
     * Send an email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        // Implement email verification notification logic here
    }

    public function isAdmin()
    {
        /**
     * Determine if the user is an admin.
     *
     * @return bool
     */
        return $this->role === 'admin';
    }

    public function delete()
    {

        return parent::delete();
    }

        /**
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        // Example: Hash the password if it's being set
        if ($this->isDirty('password')) {
            $this->password = Hash::make($this->password);
        }

        // Call the parent save method
        return parent::save($options);
    }
}
