<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function user()
    {
        $user = User::with(['roles', 'companies'])->get();
        $roles = Role::all();
        $companies = Company::all();

        return view('settings.users.index', compact('user', 'roles', 'companies'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()->min(6)],
            'role_id' => ['required', 'exists:roles,id'],
            'companies' => ['nullable', 'array'],
            'companies.*' => ['exists:companies,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'status' => 'active', // default status
            'password' => Hash::make($request->password),
        ]);

        // Assign role to user
        $role = Role::findById($request->role_id);
        $user->assignRole($role);

        // Assign companies to user if provided
        if ($request->has('companies') && is_array($request->companies)) {
            $user->companies()->sync($request->companies);
        }

        event(new Registered($user));

        return redirect()->back()->with('success', 'New user added successfully');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $companies = Company::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        $userCompanies = $user->companies->pluck('id')->toArray();

        return view('settings.users.edit', compact('user', 'roles', 'companies', 'userRoles', 'userCompanies'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', Rules\Password::defaults()->min(6)],
            'role_id' => ['required', 'exists:roles,id'],
            'companies' => ['nullable', 'array'],
            'companies.*' => ['exists:companies,id'],
        ]);

        $updateData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // Update role
        $role = Role::findById($request->role_id);
        $user->syncRoles([$role]);

        // Update companies
        if ($request->has('companies') && is_array($request->companies)) {
            $user->companies()->sync($request->companies);
        } else {
            $user->companies()->detach();
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function toggleStatus($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->status = $user->status === 'active' ? 'inactive' : 'active';
            $user->save();

            $message = $user->status === 'active' ? 'User activated successfully' : 'User deactivated successfully';
            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'User not found');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            // Detach companies before deleting user
            $user->companies()->detach();
            // Remove roles
            $user->syncRoles([]);
            // Delete user
            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully');
        } else {
            return redirect()->back()->with('error', 'User not found');
        }
    }
}
