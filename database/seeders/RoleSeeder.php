<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define sample roles
        $roles = [
            ['title' => 'admin', 'description' => 'Administrator role'],
            ['title' => 'client', 'description' => 'Regular user role'],
        ];

        // Insert roles into the database
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
