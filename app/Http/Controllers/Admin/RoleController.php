<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Logical grouping and bilingual meta for all system permissions.
     */
    public static function getPermissionGroups(): array
    {
        return [
            'Dashboard' => [
                'title' => 'ড্যাশবোর্ড (Dashboard)',
                'icon' => 'fas fa-tachometer-alt text-primary',
                'description' => 'ড্যাশবোর্ড অ্যানালিটিক্স ও মূল পরিসংখ্যান',
                'permissions' => [
                    'view-dashboard' => [
                        'label' => 'View Dashboard',
                        'bn_label' => 'ড্যাশবোর্ড অ্যাক্সেস',
                        'desc' => 'ড্যাশবোর্ড ওভারভিউ, বিক্রয় পরিসংখ্যান ও সারাংশ দেখার অনুমতি।',
                    ],
                ],
            ],
            'Orders' => [
                'title' => 'অর্ডার ও বিক্রয় (Orders)',
                'icon' => 'fas fa-shopping-cart text-success',
                'description' => 'অর্ডার ট্র্যাকিং, স্ট্যাটাস পরিবর্তন ও মেমো',
                'permissions' => [
                    'manage-orders' => [
                        'label' => 'Manage Orders',
                        'bn_label' => 'অর্ডার পরিচালনা ও ইনভয়েস',
                        'desc' => 'সমস্ত অর্ডার দেখা, ডেলিভারি স্ট্যাটাস পরিবর্তন ও প্রিন্ট ইনভয়েস।',
                    ],
                ],
            ],
            'Products' => [
                'title' => 'পণ্য ও ক্যাটালগ (Products)',
                'icon' => 'fas fa-box text-warning',
                'description' => 'পণ্য যুক্ত, দাম, স্টক ও ল্যান্ডিং মোড',
                'permissions' => [
                    'manage-products' => [
                        'label' => 'Manage Products',
                        'bn_label' => 'পণ্য যোগ, এডিট ও স্টক',
                        'desc' => 'নতুন পণ্য যুক্ত, বিবরণ এডিট, সিঙ্গেল/মাল্টি প্রোডাক্ট মোড ও স্টক কন্ট্রোল।',
                    ],
                ],
            ],
            'Reviews' => [
                'title' => 'গ্রাহক রিভিউ (Reviews)',
                'icon' => 'fas fa-star text-warning',
                'description' => 'কাস্টমার মতামত ও রেটিং প্রদর্শন',
                'permissions' => [
                    'manage-reviews' => [
                        'label' => 'Manage Reviews',
                        'bn_label' => 'রিভিউ ও রেটিং নিয়ন্ত্রণ',
                        'desc' => 'গ্রাহকদের রিভিউ দেখা, ওয়েবসাইটে প্রকাশ/গোপন (toggle) এবং ডিলিট করার অনুমতি।',
                    ],
                ],
            ],
            'Sections' => [
                'title' => 'ল্যান্ডিং সেকশন (Page Sections)',
                'icon' => 'fas fa-sliders-h text-info',
                'description' => 'হোমপেজ সেকশন অন/অফ ও কালার ডিজাইন',
                'permissions' => [
                    'manage-sections' => [
                        'label' => 'Manage Sections',
                        'bn_label' => 'সেকশন অন/অফ ও স্টাইল',
                        'desc' => 'ল্যান্ডিং পেজের ব্যানার, সুবিধা, ভিডিও, গ্যারান্টি ইত্যাদি সেকশন চালু/বন্ধ ও স্টাইলিং।',
                    ],
                ],
            ],
            'Settings' => [
                'title' => 'সাইট সেটিংস ও এসইও (Settings & SEO)',
                'icon' => 'fas fa-cog text-secondary',
                'description' => 'সাইট ইনফো, মেটা ট্যাগ ও অ্যানালিটিক্স',
                'permissions' => [
                    'manage-settings' => [
                        'label' => 'Manage Settings & SEO',
                        'bn_label' => 'সাইট ইনফো, মেটা ট্যাগ ও অ্যানালিটিক্স',
                        'desc' => 'সাইট লোগো/ফেভিকন, ফোন, মেটা টাইটেল, গুগল অ্যানালিটিক্স ও ফেসবুক পিক্সেল কোড আপডেট।',
                    ],
                ],
            ],
            'Administration' => [
                'title' => 'অ্যাডমিন ও রোল কন্ট্রোল (Administration & RBAC)',
                'icon' => 'fas fa-shield-alt text-danger',
                'description' => 'নতুন অ্যাডমিন তৈরি ও রোল/পারমিশন ব্যবস্থাপনা',
                'permissions' => [
                    'manage-admins' => [
                        'label' => 'Manage Admin Accounts',
                        'bn_label' => 'অ্যাডমিন অ্যাকাউন্ট তৈরি ও পরিচালনা',
                        'desc' => 'নতুন অ্যাডমিন যুক্ত করা, রোল নির্ধারণ, ইমেইল/পাসওয়ার্ড এডিট ও ডিলিট।',
                    ],
                    'manage-roles' => [
                        'label' => 'Manage Roles & Permissions',
                        'bn_label' => 'রোল তৈরি ও পারমিশন কন্ট্রোল',
                        'desc' => 'নতুন রোল তৈরি, বিভিন্ন পারমিশন চেক/আনচেক করে রোল কাস্টমাইজেশন ও ডিলিট।',
                    ],
                ],
            ],
        ];
    }

    /**
     * Display a listing of all roles with their assigned permissions and user counts.
     */
    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->withCount('users')->orderBy('id')->get();
        $permissionGroups = self::getPermissionGroups();

        return view('admin.roles.index', compact('roles', 'permissionGroups'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissionGroups = self::getPermissionGroups();
        $allPermissions = Permission::orderBy('name')->pluck('name')->toArray();

        return view('admin.roles.create', compact('permissionGroups', 'allPermissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:50|unique:roles,name',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.required' => 'রোল এর নাম প্রদান করুন (Role name is required).',
            'name.unique'   => 'এই নামের একটি রোল ইতিমধ্যে বিদ্যমান রয়েছে (This role name already exists).',
        ]);

        $role = Role::create([
            'name'       => trim($validated['name']),
            'guard_name' => 'web',
        ]);

        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'নতুন রোল "' . $role->name . '" সফলভাবে তৈরি করা হয়েছে এবং ' . count($permissions) . 'টি পারমিশন দেওয়া হয়েছে!');
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        $permissionGroups = self::getPermissionGroups();
        $allPermissions = Permission::orderBy('name')->pluck('name')->toArray();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissionGroups', 'allPermissions', 'rolePermissions'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:50|unique:roles,name,' . $role->id,
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.required' => 'রোল এর নাম প্রদান করুন (Role name is required).',
            'name.unique'   => 'এই নামের একটি রোল ইতিমধ্যে বিদ্যমান রয়েছে (This role name already exists).',
        ]);

        // Safeguard: Protect 'Super Admin' name and full privileges
        if ($role->name === 'Super Admin') {
            // Ensure Super Admin retains core access
            $allPermissions = Permission::pluck('name')->toArray();
            $role->syncPermissions($allPermissions);
            return redirect()->route('admin.roles.index')
                ->with('success', 'সুপার অ্যাডমিন (Super Admin) রোলের সকল পারমিশন সুরক্ষিত রাখা হয়েছে।');
        }

        $role->name = trim($validated['name']);
        $role->save();

        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'রোল "' . $role->name . '" এর তথ্য ও পারমিশন সফলভাবে আপডেট করা হয়েছে!');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        // Safeguard 1: Do not allow deleting Super Admin
        if ($role->name === 'Super Admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'সুপার অ্যাডমিন (Super Admin) রোলটি কোনোভাবেই ডিলিট করা সম্ভব নয়!');
        }

        // Safeguard 2: Check if any admin account is currently assigned to this role
        $assignedUsersCount = $role->users()->count();
        if ($assignedUsersCount > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'রোল "' . $role->name . '" ডিলিট করা যাবে না, কারণ বর্তমানে ' . $assignedUsersCount . ' জন অ্যাডমিন এই রোলে নিয়োজিত আছেন। অনুগ্রহ করে প্রথমে তাদের রোল পরিবর্তন করুন।');
        }

        $roleName = $role->name;
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'রোল "' . $roleName . '" সফলভাবে ডিলিট করা হয়েছে!');
    }
}
