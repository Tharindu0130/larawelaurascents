<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // Revoke current user tokens
        if (auth()->check()) {
            auth()->user()->tokens()->delete();
        }
        // Clear session token
        session()->forget('api_token');
        // Logout user
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
