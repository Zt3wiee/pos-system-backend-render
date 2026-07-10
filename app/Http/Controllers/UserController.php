<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // public function index()
    // {
    //     $user = User::latest()->get();
    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $user
    //     ]);
    // }


         public function index(Request $request){
          $search = $request->search;
          $users = User::when($search,function($query) use ($search){
            $query->where('name', 'like', "%$search%");
          })
          ->latest()->get();
          return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }




    
    public function currentUser(Request $request){
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,cashier'
        ]);
        $user = User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => bcrypt($validateData['password']),
            'role' => $validateData['role']
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        $validateData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|in:admin,cashier'
        ]);
        $user->update($validateData);
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,

        ]);
    }

    public function destroy($id){
        $user = User::findOrfail($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
