<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function showAdministrators()
    {
        $users = User::whereIn('account_type', ['secretary', 'captain', 'official'])
                    ->get();
        return view('secretary.User.administrator', compact('users'));
    }

    public function showResidents()
    {
        $users = User::where('account_type', 'resident')
                    ->get();
        return view('secretary.User.resident', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'account_type' => ['sometimes', 'required', 'string', Rule::in(['administrator', 'official', 'captain', 'resident'])],
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'User details updated successfully.');
    }

    public function changePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
} 