<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation failed. Please check your input.');
        }

        // Determine if input is email or username
        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Fetch user
        $user = User::where($field, $login)->first();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'User not found.')
                ->withInput();
        }

        // Check MD5 password and rehash if necessary
        if (strlen($user->password) == 32 && md5($request->input('password')) === $user->password) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
            Auth::login($user);
            return redirect()->route('dashboard');
        }

        // Check bcrypt password
        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()->back()
                ->with('error', 'Invalid credentials. Please try again.')
                ->withInput();
        }

        // Log in user
        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function check(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $user = User::where('name', $request->name)
            ->where('email', $request->email)
            ->first();

        if ($user) {
            session()->flash('user', $user);
            session()->flash('status', 'You can now set a new password.');
            return redirect()->back();
        } elseif (User::where('name', $request->name)->exists() || User::where('email', $request->email)->exists()) {
            return redirect()->back()->withErrors(['email' => 'Either name or email is incorrect.']);
        } else {
            return redirect()->back()->withErrors(['email' => 'User does not exist.']);
        }
    }


    public function resetPassword(Request $request)
    {
        // Validate request including password minimum 8 characters + confirmed
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $user->password = Hash::make($request->password);
            $user->save();

            // Redirect to login page with success message
            return redirect()->route('welcome')->with('status', 'Password updated successfully. You may now log in.');
        } catch (\Exception $e) {
            // If error, redirect back to forgot/reset page with input & error message
            return redirect()->back()
                ->withErrors(['general' => 'Failed to reset password, please try again.'])
                ->withInput();
        }
    }


    /**
     * Validate login fields
     */
    public function validateLoginField(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        return response()->json([
            'success' => !$validator->fails(),
            'errors' => $validator->errors()
        ], $validator->fails() ? 422 : 200);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Letak ke cookie, tahan 1 minit
        return redirect('/')
            ->withCookie(cookie('logout_message', 'Successfully logged out.', 1));
    }

}
