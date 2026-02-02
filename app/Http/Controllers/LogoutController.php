<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // Revoke the current user's tokens directly (no HTTP call needed)
        if (auth()->check()) {
            auth()->user()->tokens()->delete();
        }

        // Clear the session token
        session()->forget('api_token');

        // Logout the user
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
