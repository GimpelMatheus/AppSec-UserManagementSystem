<?php

use app\Http\Controllers\ProfileController;
use app\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\Auth\LoginController;
use app\Http\Controllers\Auth\VerifyEmailController;
use app\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\Validator;
use app\Models\User; 
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');

    Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.users.create');

    Route::post('/admin/users', function () {
        // Validate user creation request
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle user creation logic
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);

        return redirect()->route('admin.users.index');
    })->name('admin.users.store');

    Route::get('/admin/users/{user}/edit', function (User $user) {
        // Ensure $user is an instance of User
        if (!$user instanceof User) {
            throw new UnauthorizedException();
        }
        return view('admin.users.edit', compact('user'));
    })->name('admin.users.edit');

    Route::put('/admin/users/{user}', function (User $user) {
        // Ensure $user is an instance of User
        if (!$user instanceof User) {
            throw new UnauthorizedException();
        }

        // Validate user update request
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle user update logic
        $user->update(request()->only('name', 'email'));

        return redirect()->route('admin.users.index');
    })->name('admin.users.update');

    Route::delete('/admin/users/{user}', function (User $user) {
        // Ensure $user is an instance of User
        if (!$user instanceof User) {
            throw new UnauthorizedException();
        }

        // Handle user deletion logic
        $user->delete();

        return redirect()->route('admin.users.index');
    })->name('admin.users.destroy');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', function () {
        // Ensure user is an instance of User
        $user = auth()->user();
        if (!$user instanceof User) {
            throw new UnauthorizedException();
        }

        // Validate profile update request
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Handle profile update logic
        $user->update(request()->only('name', 'email'));

        return redirect()->route('profile.edit');
    })->name('profile.update');

    Route::delete('/profile', function () {
        // Ensure user is an instance of User
        $user = auth()->user();
        if (!$user instanceof User) {
            throw new UnauthorizedException();
        }

        // Handle profile deletion logic
        $user->delete();
        return redirect()->route('login');
    })->name('profile.destroy');
});

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke']);
Route::post('/email/resend', [VerifyEmailController::class, 'resend'])->name('verification.resend');

require __DIR__.'/auth.php';
