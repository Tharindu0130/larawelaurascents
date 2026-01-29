<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        \Log::info('📝 API: Registration attempt', ['email' => $request->email]);
        
        try {
            // 1. Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
            ]);

            // 2. Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'user_type' => 'customer',
            ]);

            // 3. Create token
            $token = $user->createToken('auth_token')->plainTextToken;

            \Log::info('✅ API: Registration successful', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // 4. Return response
            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'user_type' => $user->user_type ?? 'customer',
                        'profile_photo_url' => $user->profile_photo_url ?? null,
                        'created_at' => $user->created_at,
                    ],
                ],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('⚠️ API: Registration validation failed', [
                'email' => $request->email,
                'errors' => $e->errors()
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('❌ API: Registration failed', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        \Log::info('🔐 API: Login attempt', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);
        
        try {
            // 1. Validate the request
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // 2. Check if user exists
            $user = User::where('email', $request->email)->first();

            // 3. Verify password
            if (!$user || !Hash::check($request->password, $user->password)) {
                \Log::warning('⚠️ API: Login failed - Invalid credentials', [
                    'email' => $request->email,
                    'ip' => $request->ip()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password'
                ], 401);
            }

            // 4. Create Token (Sanctum)
            $token = $user->createToken('auth_token')->plainTextToken;

            \Log::info('✅ API: Login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'user_type' => $user->user_type
            ]);

            // 5. Return response in Flutter-compatible format
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'user_type' => $user->user_type ?? 'customer',
                        'profile_photo_url' => $user->profile_photo_url ?? null,
                        'created_at' => $user->created_at,
                    ],
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('⚠️ API: Login validation failed', [
                'email' => $request->email,
                'errors' => $e->errors()
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('❌ API: Login failed', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        \Log::info('🚪 API: Logout request', [
            'user_id' => $request->user()->id,
            'email' => $request->user()->email
        ]);
        
        try {
            // Revoke the token that was used to authenticate the current request
            $request->user()->currentAccessToken()->delete();

            \Log::info('✅ API: Logout successful', [
                'user_id' => $request->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('❌ API: Logout failed', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}