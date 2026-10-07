<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherAuthController extends Controller
{
    public function showLogin()
    {
        // Already logged in? Go straight to the dashboard
        if (Auth::check()) {
            return redirect()->route('teacher.students.index');
        }

        return view('teacher.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Enter your email.',
            'email.email'       => 'Enter a valid email address.',
            'password.required' => 'Enter your password.',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['login' => 'Email or password is incorrect.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('teacher.students.index'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}