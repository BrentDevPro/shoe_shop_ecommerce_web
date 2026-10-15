<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User; // Explicitly import the User model

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.settings', compact('user'));
    }

    public function update(Request $request)
    {
        // Explicitly fetch the Eloquent User model to fix the "unidentified method" error
        $user = User::find(Auth::id());

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:new_password', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Handle Password Update if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Update Name and Email
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save(); // The editor now knows this is an Eloquent Model

        return back()->with('success', 'Account settings successfully updated.');
    }
}