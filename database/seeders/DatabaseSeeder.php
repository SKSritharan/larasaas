<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $roles = [
            ['name' => 'admin'],
            ['name' => 'user'],
        ];

        $permissions = [
            ['name' => 'create plan'],
            ['name' => 'edit plan'],
            ['name' => 'delete plan'],
            ['name' => 'view plan'],
            ['name' => 'view all plans'],
            ['name' => 'create feature'],
            ['name' => 'edit feature'],
            ['name' => 'delete feature'],
            ['name' => 'view feature'],
            ['name' => 'view all features'],
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role['name']]);
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission['name']]);
        }

        $role = Role::findByName('admin');
        $role->givePermissionTo(Permission::all());

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
        ]);

        $user->assignRole('admin');

        $this->call(FeatureSeeder::class);
    }
}
