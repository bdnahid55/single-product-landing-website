<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of admin accounts.
     */
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new admin account.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created admin account in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required'      => 'Please enter the admin name.',
            'email.required'     => 'Please enter an email address.',
            'email.unique'       => 'This email address is already in use by another account.',
            'password.required'  => 'Please enter a secure password.',
            'password.min'       => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => strtolower(trim($validated['email'])),
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_admin' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'New admin account for "' . $validated['name'] . '" created successfully!');
    }

    /**
     * Show the form for editing the specified admin account.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified admin account in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required'      => 'Please enter the admin name.',
            'email.required'     => 'Please enter an email address.',
            'email.unique'       => 'This email address is already in use by another account.',
            'password.min'       => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $data = [
            'name'     => $validated['name'],
            'email'    => strtolower(trim($validated['email'])),
            'phone'    => $validated['phone'],
            'is_admin' => true,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin account "' . $user->name . '" updated successfully!');
    }

    /**
     * Remove the specified admin account from storage.
     */
    public function destroy(User $user)
    {
        // Guard 1: Cannot delete logged in admin
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own admin account while logged in.');
        }

        // Guard 2: Cannot delete the only remaining admin
        if (User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'Cannot delete the only remaining admin account in the system.');
        }

        $deletedName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin account "' . $deletedName . '" deleted successfully.');
    }
}
