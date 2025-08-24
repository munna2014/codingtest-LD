<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $users = User::all();
        return view('dashboard', compact('users'));
    }

    // Update user data via AJAX
    public function updateUser(Request $request, $id)
    {
        // Implementation here
    }
}