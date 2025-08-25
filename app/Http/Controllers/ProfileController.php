<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        // get logged-in user
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request)
    {
        $user = \App\Models\User::find(Auth::id());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // prevent duplicate emails
            ],
        ]);

        $user->fill($validated);
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    /**
     * Delete the user account.
     */
    public function destroy(Request $request)
    {
        $user = \App\Models\User::find(Auth::id());

        Auth::logout();

        if ($user) {
            $user->delete();
        }

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}