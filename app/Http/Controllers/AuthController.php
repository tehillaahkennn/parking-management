<?php

namespace App\Http\Controllers;

use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('parking.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials + ['is_active' => true])) {            $request->session()->regenerate();

            AuditLogger::log(
                $request,
                'LOGIN',
                'User logged in successfully: '
                    . $request->user()->email
            );

            return redirect()->intended(route('parking.index'));
        }

        return back()
            ->withErrors([
                'email' => 'The email or password is incorrect.',
            ])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        AuditLogger::log(
            $request,
            'LOGOUT',
            'User logged out: '
                . $request->user()->email
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'You have logged out successfully.');
    }
}
