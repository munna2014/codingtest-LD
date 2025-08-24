<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
       $users = User::paginate(10); // 10 users per page

        return view('dashboard', compact('users'));
    }

    // Update user data via AJAX
    public function updateUser(Request $request, $id)
    {
        // Implementation here
    }
}