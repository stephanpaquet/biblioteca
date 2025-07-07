<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Check if user has admin permissions
        if (!$request->user()->can('manage users')) {
            abort(403, 'You do not have permission to access the admin panel.');
        }

        $users = User::with('roles')->paginate(10);
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return Inertia::render('Admin/Index', [
            'users' => $users,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function assignRole(Request $request, User $user)
    {
        // Check if user has permission to assign roles
        if (!$request->user()->can('assign roles')) {
            return response()->json(['message' => 'You do not have permission to assign roles.'], 403);
        }

        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);

        return response()->json(['message' => 'Role assigned successfully']);
    }

    public function removeRole(Request $request, User $user)
    {
        // Check if user has permission to assign roles
        if (!$request->user()->can('assign roles')) {
            return response()->json(['message' => 'You do not have permission to remove roles.'], 403);
        }

        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->removeRole($request->role);

        return response()->json(['message' => 'Role removed successfully']);
    }

    public function userLibraries(Request $request)
    {
        // Check if user has permission to view all libraries
        if (!$request->user()->can('view all libraries')) {
            abort(403, 'You do not have permission to view all libraries.');
        }

        $users = User::with(['books' => function ($query) {
            $query->withPivot('status', 'created_at');
        }])->paginate(10);

        return Inertia::render('Admin/Libraries', [
            'users' => $users,
        ]);
    }
}
