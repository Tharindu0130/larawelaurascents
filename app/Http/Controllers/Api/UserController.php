<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * ASSIGNMENT CRITERIA: User Management API
 * 
 * Provides API endpoints for managing users (customers)
 * Demonstrates: RESTful API for user CRUD operations
 */
class UserController extends Controller
{
    /**
     * Get all customers
     * ASSIGNMENT: RESTful API endpoint for user listing
     */
    public function index(Request $request)
    {
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

    /**
     * Get a specific user
     */
    public function show(string $id)
    {
        $user = User::with(['orders.product'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update user status
     */
    public function update(Request $request, string $id)
    {
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

    /**
     * Delete a user
     */
    public function destroy(string $id)
    {
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
