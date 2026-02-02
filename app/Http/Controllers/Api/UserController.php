<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    // Ensure only admins can access user management
    private function ensureAdmin(Request $request): void
    {
        if ($request->user()->user_type !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }
    }

    public function index(Request $request)
    {
        $this->ensureAdmin($request);
        
        $query = User::where('user_type', 'customer');
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

   
    public function show(Request $request, string $id)
    {
        $this->ensureAdmin($request);
        
        $user = User::with(['orders.product'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    // Update user status
    public function update(Request $request, string $id)
    {
        $this->ensureAdmin($request);
        
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'is_active' => 'sometimes|boolean',
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $id,
        ]);
        
        $user->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    // Delete a user
    public function destroy(Request $request, string $id)
    {
        $this->ensureAdmin($request);
        
        $user = User::findOrFail($id);
        // Delete related orders first
        $user->orders()->delete();
        // Delete user
        $user->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
