<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Show dashboard with users list
     */
    public function index()
    {
        $users = User::all();
        return view('dashboard', compact('users'));
    }

    /**
     * Return all users (JSON - for AJAX/DataTables)
     */
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Return DataTable formatted response
     */
    public function datatable()
    {
        $users = User::select('id', 'name', 'email', 'created_at')->get();
        $data = $users->map(function ($user) {
            return [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'created_at'=> $user->created_at->format('Y-m-d'),
                'actions'   => '<button class="btn btn-sm btn-primary editUser"
                                    data-id="' . $user->id . '"
                                    data-name="' . $user->name . '"
                                    data-email="' . $user->email . '">Edit</button>',
            ];
        });
        return response()->json(['data' => $data]);
    }

    /**
     * Update user (AJAX request)
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validated = $request->validate([
                'name'  => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
            ]);

            $user->update($validated);

            // Handle AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully',
                    'user' => $user
                ]);
            }

            return redirect()->back()->with('success', 'User updated successfully');
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating user: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()->with('error', 'Error updating user');
        }
    }
}