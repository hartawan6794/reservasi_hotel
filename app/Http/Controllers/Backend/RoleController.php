<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PermissionExport;
use App\Http\Controllers\Controller;
use App\Imports\PermissionImport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Permission CRUD
     */
    public function AllPermission()
    {
        $permissions = Permission::latest()->get();

        // dd($permissions);
        return view('backend.pages.permission.all_permission', compact('permissions'));
    }

    public function AddPermission()
    {
        return view('backend.pages.permission.add_permission');
    }

    public function StorePermission(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'group_name' => 'required|string|max:255',
        ]);

        Permission::create($validated);

        return redirect()->route('all.permission')->with('success', 'Permission created successfully.');
    }

    public function EditPermission($id)
    {
        $permission = Permission::findOrFail($id);
        return view('backend.pages.permission.edit_permission', compact('permission'));
    }

    public function UpdatePermission(Request $request)
    {
        $permission = Permission::findOrFail($request->id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'group_name' => 'required|string|max:255',
        ]);

        $permission->update($validated);

        return redirect()->route('all.permission')->with('success', 'Permission updated successfully.');
    }

    public function DeletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('all.permission')->with('success', 'Permission deleted successfully.');
    }

    public function ImportPermission()
    {
        return view('backend.pages.permission.import_permission');
    }

    public function Export()
    {
        return Excel::download(new PermissionExport, 'permissions.xlsx');
    }

    public function Import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new PermissionImport, $request->file('import_file'));

        return redirect()->route('all.permission')->with('success', 'Permissions imported successfully.');
    }

    /**
     * Role CRUD
     */
    public function AllRoles()
    {
        $roles = Role::latest()->get();
        return view('backend.pages.roles.all_roles', compact('roles'));
    }

    public function AddRoles()
    {
        return view('backend.pages.roles.add_roles');
    }

    public function StoreRoles(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create($validated);

        return redirect()->route('all.roles')->with('success', 'Role created successfully.');
    }

    public function EditRoles($id)
    {
        $roles = Role::findOrFail($id);
        return view('backend.pages.roles.edit_roles', compact('roles'));
    }

    public function UpdateRoles(Request $request)
    {
        $role = Role::findOrFail($request->id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update($validated);

        return redirect()->route('all.roles')->with('success', 'Role updated successfully.');
    }

    public function DeleteRoles($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('all.roles')->with('success', 'Role deleted successfully.');
    }

    /**
     * Role & Permission mapping
     */
    public function AddRolesPermission()
    {
        $roles = Role::orderBy('name')->get();
        $permission_groups = User::getpermissionGroups();

        return view('backend.pages.rolesetup.add_roles_permission', compact('roles', 'permission_groups'));
    }

    public function RolePermissionStore(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission' => 'required|array',
            'permission.*' => 'exists:permissions,id',
        ]);

        $role = Role::findOrFail($validated['role_id']);
        $role->syncPermissions($validated['permission']);

        return redirect()->route('all.roles.permission')->with('success', 'Permissions assigned to role successfully.');
    }

    public function AllRolesPermission()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        return view('backend.pages.rolesetup.all_roles_permission', compact('roles'));
    }

    public function AdminEditRoles($id)
    {
        $role = Role::findOrFail($id);
        $permission_groups = User::getpermissionGroups();

        return view('backend.pages.rolesetup.edit_roles_permission', compact('role', 'permission_groups'));
    }

    public function AdminRolesUpdate(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'permission' => 'required|array',
            'permission.*' => 'exists:permissions,id',
        ]);

        $role->syncPermissions($validated['permission']);

        return redirect()->route('all.roles.permission')->with('success', 'Role permissions updated successfully.');
    }

    public function AdminDeleteRoles($id)
    {
        $role = Role::findOrFail($id);
        $role->syncPermissions([]); // detach all permissions before delete
        $role->delete();

        return redirect()->route('all.roles.permission')->with('success', 'Role permissions entry deleted.');
    }
}