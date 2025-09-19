<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('settings.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('settings.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            // Get permission objects by IDs
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        return view('settings.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('settings.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            // Get permission objects by IDs
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'Cannot delete role. It is assigned to users.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
