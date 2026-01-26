<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*** Show the user login form (customer)*/
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /** Handle user (customer) login. Separate from admin login.*/
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['email' => 'Invalid email or password'])
                ->withInput();
        }

        if ($user->user_type !== 'customer') {
            return back()
                ->withErrors(['email' => 'Use Admin Login for admin accounts.'])
                ->withInput();
        }

        Auth::login($user, (bool) $request->remember);
        $this->storeSanctumToken($user);

        return redirect()->intended(route('home'));
    }

    /*** Show the admin login form (separate route/page)*/
    public function showAdminLoginForm()
    {
        return view('auth.login-admin');
    }

    /*** Handle admin login. Separate from user login.*/
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['email' => 'Invalid email or password'])
                ->withInput();
        }

        if ($user->user_type !== 'admin') {
            return back()
                ->withErrors(['email' => 'Admin access only. Use User Login for customer accounts.'])
                ->withInput();
        }

        Auth::login($user, (bool) $request->remember);
        $this->storeSanctumToken($user);

        return redirect()->intended(route('admin.dashboard'));
    }

    /*** Create Sanctum token and store in session for API calls (frontend CRUD via API).*/
    private function storeSanctumToken(User $user): void
    {
        $token = $user->createToken('web')->plainTextToken;
        session(['api_token' => $token]);
    }
}
