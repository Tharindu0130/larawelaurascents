<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // Get the token from session
        $token = session('api_token');

        if ($token) {
            // Call API logout endpoint
            Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])->post(url('/api/logout'));
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
