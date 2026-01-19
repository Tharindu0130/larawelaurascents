<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Authenticate user via web session
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        // Login user via web session
        auth()->login($user);

        // Get API token by calling the API login endpoint
        $response = Http::post(url('/api/login'), [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            // Store the plain-text token in session
            $token = $response->body();
            session(['api_token' => $token]);
        }

        // Redirect based on user type
        if ($user->user_type === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard');
    }
}
