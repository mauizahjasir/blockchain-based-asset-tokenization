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
            ['title' => 'Admin', 'description' => 'Administrator role'],
            ['title' => 'Client', 'description' => 'Regular user role'],
        ];

        // Insert roles into the database
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
