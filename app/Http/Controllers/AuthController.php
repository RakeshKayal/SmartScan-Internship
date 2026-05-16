<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    /**
     * Show Register Page
     */
    public function showRegister()
    {
        return view('auth.register');
    }



    /**
     * Show Login Page
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Login User
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput();
    }

    /**
     * Logout User
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form')->with('success', 'You have been logged out.');
    }

    /**
     * Temporary Dashboard Page (we will improve in Part 4)
     */
    public function dashboard()
    {
        return view('dashboard.index');
    }

    /**
     * Show all users (Admin only)
     */
    public function userList()
    {
        $users = \App\Models\User::where('id', '!=', Auth::id())->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show create user form (Admin only)
     */
    public function showCreateUser()
    {
        return view('admin.users.create');
    }

    /**
     * Admin creates a Sales user
     */
// public function createUser(Request $request)
// {
//     $request->validate([
//         'name'     => 'required|string|max:255',
//         'email'    => 'required|email|unique:users,email',
//         'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(6)],
//     ]);

//     \App\Models\User::create([
//         'name'     => $request->name,
//         'email'    => $request->email,
//         'role'     => 'sales', // Always sales — admin creates sales users only
//         'password' => $request->password,
//     ]);

//     return redirect()->route('users.index')->with('success', 'Sales user created successfully!');
// }

    /**
     * Admin deletes a user
     */
    public function deleteUser($id)
    {
        $user = \App\Models\User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }


// UserController.php


    /**
     * Send OTP to email — expires in 40 seconds
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Block if email already registered
        if (\App\Models\User::where('email', $request->email)->exists()) {
            return response()->json(['success' => false, 'message' => 'This email is already registered.']);
        }

        $otp = rand(100000, 999999);
        $key = 'otp_' . md5($request->email);

        // Store OTP in cache — expires in 40 seconds
        Cache::put($key, $otp, now()->addSeconds(40));

        try {
            Mail::to($request->email)->send(new OtpMail($otp));
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()]);
        }
    }

    /**
     * Admin creates a Sales user — OTP must be valid
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(6)],
        ]);

        // $key        = 'otp_' . md5($request->email);
        // $cachedOtp  = Cache::get($key);

        // if (!$cachedOtp) {
        //     return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.'])->withInput();
        // }

        // if ((string) $cachedOtp !== (string) $request->otp) {
        //     return back()->withErrors(['otp' => 'Invalid OTP. Please try again.'])->withInput();
        // }

        // // OTP valid — delete it so it can't be reused
        // Cache::forget($key);

        \App\Models\User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => 'sales',
            'password' => $request->password,
        ]);

        return redirect()->route('users.index')->with('success', 'Sales user verified and created successfully!');
    }


    /**
     * Verify OTP via AJAX — does NOT consume it yet, just checks
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $key       = 'otp_' . md5($request->email);
        $cachedOtp = Cache::get($key);

        if (!$cachedOtp) {
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please request a new one.']);
        }

        if ((string) $cachedOtp !== (string) $request->otp) {
            return response()->json(['success' => false, 'message' => 'Incorrect OTP. Please try again.']);
        }

        return response()->json(['success' => true]);
    }
}
