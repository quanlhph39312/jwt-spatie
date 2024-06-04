<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class PermissionController extends Controller
{

    // Tạo vai trò
    public function createRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        return response()->json(['role' => $role], 201);
    }

    // Phân vai trò
    public function hasRole($id)
    {
        $user = User::findOrFail($id);
        $roles = $this->getAllRoles();
        $permissions = $this->getAllPermissions();
        $assignedPermissions = $user ? $user->getAllPermissions()->pluck('id')->toArray() : [];

        return response()->json([
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            'assignedPermissions' => $assignedPermissions,
        ], 200);
    }

    // Thêm quyền cho vai trò
    public function assignPermissionsToRole($roleId, Request $request)
    {
        $role = Role::findOrFail($roleId);
        $permissionIds = $request->input('permission_ids', []);

        $role->permissions()->sync($permissionIds);

        return response()->json(['message' => 'Permissions assigned to role successfully'], 200);
    }


    // Cập nhật vai trò của người dùng
    public function updateRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $role = Role::where('name', $validated['role_name'])->firstOrFail();
        $user->syncRoles([$role->id]);
        $permissions = $role->permissions()->pluck('id');

        return response()->json([
            'user' => $user,
            'roles' => $this->getAllRoles(),
            'permissions' => $this->getAllPermissions(),
            'assignedPermissions' => $permissions,
        ], 200);
    }

    // Thêm quyền
    public function createPermission(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        $permission = Permission::create(['name' => $validated['name']]);
        return response()->json(['permission' => $permission], 201);
    }

    // Kiểm tra quyền của người dùng
    public function hasPermission($id)
    {
        $user = User::with('roles.permissions', 'permissions')->findOrFail($id);
        $roles = $this->getAllRoles();
        $permissions = $this->getAllPermissions();
        $userRole = $user->roles->first();
        $assignedPermissions = $userRole ? $userRole->permissions->pluck('id')->toArray() : [];
        $userPermissions = $user->permissions->pluck('id')->toArray();

        return response()->json([
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole,
            'permissions' => $permissions,
            'userPermissions' => $userPermissions,
            'assignedPermissions' => $assignedPermissions,
        ], 200);
    }
    // Cập nhật quyền của người dùng
    public function updatePermission(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $permissionNames = Permission::whereIn('id', $request->permission_id)->pluck('name')->toArray();
        $user->syncPermissions($permissionNames);

        return response()->json([
            'user' => $user,
            'permissions' => $user->permissions,
        ], 200);
    }

    private function getAllRoles()
    {
        return Cache::remember('roles', 60 * 60, function () {
            return Role::orderBy('id', 'DESC')->get();
        });
    }

    private function getAllPermissions()
    {
        return Cache::remember('permissions', 60 * 60, function () {
            return Permission::orderBy('id', 'DESC')->get();
        });
    }
}
