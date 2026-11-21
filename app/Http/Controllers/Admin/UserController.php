<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of admin accounts with their assigned roles.
     */
    public function index()
    {
        $users = User::with('roles')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new admin account.
     */
    public function create()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created admin account in storage with assigned role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|exists:roles,name',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required'      => 'Please enter the admin name.',
            'email.required'     => 'Please enter an email address.',
            'email.unique'       => 'This email address is already in use by another account.',
            'role.required'      => 'Please select an administrative role.',
            'password.required'  => 'Please enter a secure password.',
            'password.min'       => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => strtolower(trim($validated['email'])),
            'phone'    => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_admin' => true,
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', 'New admin account for "' . $validated['name'] . '" with role [' . $validated['role'] . '] created successfully!');
    }

    /**
     * Show the form for editing the specified admin account.
     */
    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::with('permissions')->orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified admin account in storage.
     */
    public function update(Request $request, User $user)
    {
        // 1. Re-authentication before changing password
        if ($request->filled('password')) {
            $request->validate([
                'current_password' => ['required', 'current_password'],
            ], [
                'current_password.required'         => 'পাসওয়ার্ড পরিবর্তনের পূর্বে আপনার বর্তমান পাসওয়ার্ড প্রদান করে রি-অথেনটিকেশন (Re-authentication) করুন।',
                'current_password.current_password' => 'আপনার প্রদত্ত বর্তমান পাসওয়ার্ডটি সঠিক নয় (Invalid current password)।',
            ]);
        } elseif ($request->filled('current_password')) {
            return back()->withInput()->withErrors([
                'password' => 'পাসওয়ার্ড পরিবর্তন করতে চাইলে নতুন পাসওয়ার্ডের ঘরে পাসওয়ার্ডটি লিখুন (Please enter new password).',
            ]);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|exists:roles,name',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required'      => 'Please enter the admin name.',
            'email.required'     => 'Please enter an email address.',
            'email.unique'       => 'This email address is already in use by another account.',
            'role.required'      => 'Please select an administrative role.',
            'password.min'       => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Safeguard: Do not allow demoting the only remaining Super Admin
        if ($user->hasRole('Super Admin') && $validated['role'] !== 'Super Admin') {
            $otherSuperAdminsCount = User::role('Super Admin')->where('id', '!=', $user->id)->count();
            if ($otherSuperAdminsCount === 0) {
                return back()->with('error', 'Cannot demote the only remaining Super Admin in the system.');
            }
        }

        $data = [
            'name'     => $validated['name'],
            'email'    => strtolower(trim($validated['email'])),
            'phone'    => $validated['phone'] ?? null,
            'is_admin' => true,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin account "' . $user->name . '" updated successfully!');
    }

    /**
     * Remove the specified admin account from storage.
     */
    public function destroy(User $user)
    {
        // Guard 1: Cannot delete currently logged in admin
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own admin account while logged in.');
        }

        // Guard 2: Cannot delete the only remaining admin in the system
        if (User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'Cannot delete the only remaining admin account in the system.');
        }

        // Guard 3: Cannot delete the only Super Admin
        if ($user->hasRole('Super Admin')) {
            $otherSuperAdminsCount = User::role('Super Admin')->where('id', '!=', $user->id)->count();
            if ($otherSuperAdminsCount === 0) {
                return back()->with('error', 'Cannot delete the only remaining Super Admin account in the system.');
            }
        }

        $deletedName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin account "' . $deletedName . '" deleted successfully.');
    }
}
