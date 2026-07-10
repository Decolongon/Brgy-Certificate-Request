<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $adminRole = User::factory()->create([
            'name' => 'Rusty Decolongon',
            'email' => 'decolongonrusty23@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->assignRole($admin);

        $resident = Role::firstOrCreate(['name' => 'resident']);

        foreach ($this->adminPermissions() as $permission) {
            $permission = Permission::firstOrCreate(['name' => $permission]);

            $admin->givePermissionTo($permission);
        }

        foreach ($this->residentPermissions() as $permission) {
            $permission = Permission::firstOrCreate(['name' => $permission]);
            $resident->givePermissionTo($permission);
        }
    }

    protected function adminPermissions(): array
    {
        return [
            'view_certificate_requests',
            'view_any_certificate_requests',
            'create_certificate_requests',
            'update_certificate_requests',
            'delete_certificate_requests',

            'view_brgy_certificates',
            'view_any_brgy_certificates',
            'create_brgy_certificates',
            'update_brgy_certificates',
            'delete_brgy_certificates',
        ];
    }

    protected function residentPermissions(): array
    {
        return [
            'view_certificate_requests',
            'create_certificate_requests',
        ];
    }
}
