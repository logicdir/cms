<?php

namespace App\Modules\User\Database\Seeders;

use App\Modules\User\Models\Permission;
use App\Modules\User\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Permissions
        $permissions = [
            'Core' => [
                'settings.view' => 'View system settings',
                'settings.edit' => 'Edit system settings',
            ],
            'User' => [
                'users.view' => 'View users',
                'users.create' => 'Create new users',
                'users.edit' => 'Edit existing users',
                'users.delete' => 'Delete users',
                'roles.manage' => 'Manage roles and permissions',
            ],
            'Content' => [
                'content.view' => 'View content',
                'content.create' => 'Create content',
                'content.edit' => 'Edit content',
                'content.delete' => 'Delete content',
                'content.publish' => 'Publish content',
            ],
            'Media' => [
                'media.view' => 'View media gallery',
                'media.upload' => 'Upload media files',
                'media.delete' => 'Delete media files',
            ],
        ];

        $permissionInstances = [];
        foreach ($permissions as $module => $modulePermissions) {
            foreach ($modulePermissions as $slug => $description) {
                $permissionInstances[$slug] = Permission::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => Str::title(str_replace(['.', '-'], ' ', $slug)),
                        'module' => $module,
                        'description' => $description,
                    ]
                );
            }
        }

        // 2. Create Roles
        $roles = [
            'super-admin' => [
                'name' => 'Super Admin',
                'description' => 'Full access to everything',
                'is_super_admin' => true,
                'permissions' => array_keys($permissionInstances),
            ],
            'admin' => [
                'name' => 'Administrator',
                'description' => 'System management access',
                'permissions' => [
                    'settings.view', 'settings.edit',
                    'users.view', 'users.create', 'users.edit', 'roles.manage',
                    'content.view', 'content.create', 'content.edit', 'content.publish',
                    'media.view', 'media.upload',
                ],
            ],
            'editor' => [
                'name' => 'Editor',
                'description' => 'Content management access',
                'permissions' => [
                    'content.view', 'content.create', 'content.edit', 'content.delete', 'content.publish',
                    'media.view', 'media.upload',
                ],
            ],
            'author' => [
                'name' => 'Author',
                'description' => 'Create and edit own content',
                'permissions' => [
                    'content.view', 'content.create', 'content.edit',
                    'media.view', 'media.upload',
                ],
            ],
            'subscriber' => [
                'name' => 'Subscriber',
                'description' => 'View content only',
                'permissions' => ['content.view'],
            ],
        ];

        foreach ($roles as $slug => $data) {
            $rolePermissions = $data['permissions'] ?? [];
            unset($data['permissions']);

            $role = Role::updateOrCreate(
                ['slug' => $slug],
                $data
            );

            $ids = array_map(fn($slug) => $permissionInstances[$slug]->id, $rolePermissions);
            $role->permissions()->sync($ids);
        }
    }
}
