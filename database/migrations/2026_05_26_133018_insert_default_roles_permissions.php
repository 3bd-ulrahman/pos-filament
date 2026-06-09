<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roleStructure = [
            'super_admin' => [
                'users' => 'c,r,u,d',
                'categories' => 'c,r,u,d',
                'products' => 'c,r,u,d',
                'orders' => 'c,r,u,d',
            ],
            'admin' => [
                'users' => 'c,r,u',
                'categories' => 'c,r,u',
                'products' => 'c,r,u',
                'orders' => 'c,r,u',
            ],
            'accountant' => [
                'users' => 'r',
                'categories' => 'r',
                'products' => 'r',
                'orders' => 'r',
            ],
        ];

        $mapPermission = collect([
            'c' => 'create',
            'r' => 'read',
            'u' => 'update',
            'd' => 'delete',
        ]);

        foreach ($roleStructure as $key => $modules) {

            // Create a new role
            $role = Role::query()->firstOrCreate([
                'name' => $key,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'description' => ucwords(str_replace('_', ' ', $key)),
            ]);
            $permissions = [];

            // Reading role permission modules
            foreach ($modules as $module => $value) {
                foreach (explode(',', $value) as $perm) {
                    $permissionValue = $mapPermission->get($perm);

                    $permissions[] = Permission::query()->firstOrCreate([
                        'name' => $module.'-'.$permissionValue,
                        'display_name' => ucfirst($permissionValue).' '.ucfirst($module),
                        'description' => ucfirst($permissionValue).' '.ucfirst($module),
                    ])->id;
                }
            }

            // Add all permissions to the role
            $role->permissions()->sync($permissions);

            // Create default user for each role
            $user = User::query()->create([
                'name' => ucwords(str_replace('_', ' ', $key)),
                'email' => $key.'@app.com',
                'password' => bcrypt('password'),
            ]);

            $user->addRole($role);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
