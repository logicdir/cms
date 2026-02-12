<?php

namespace App\Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\User\Models\Permission;
use App\Modules\User\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Role::class);

        return Inertia::render('Admin/Roles/Index', [
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::all()->groupBy('module'),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:roles,slug',
            'description' => 'nullable|string',
        ]);

        Role::create($request->all());

        return back()->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => "required|string|unique:roles,slug,{$role->id}",
            'description' => 'nullable|string',
        ]);

        $role->update($request->all());

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        if ($role->is_super_admin) {
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }

    public function syncPermissions(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $permissionIds = $request->input('permissions', []);
        $role->syncPermissions($permissionIds);

        return back()->with('success', 'Permissions synced successfully.');
    }
}
