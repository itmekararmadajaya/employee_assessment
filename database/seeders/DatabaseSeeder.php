<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superadmin = User::updateOrCreate([
            'email' => 'fajar.prayoga@students.amikom.ac.id'
        ],[
           'name' => 'Fajar Aji Prayoga',
           'nik' => 'F201',
           'password' => Hash::make('password')
        ]);

        $role = Role::create(['name' => 'superadmin']);

        $superadmin->assignRole($role);

        //Run Seeder Role
        $this->call(RoleSeeder::class);
        $this->call(AdminRoleSeeder::class);
    }
}
