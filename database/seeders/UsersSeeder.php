<?php

namespace Database\Seeders;

use App\Facades\MultichainService;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $walletAddress = MultichainService::getAddressWithPermission('admin');

        $data = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => Carbon::now(),
            'wallet_address' => !empty($walletAddress) ? $walletAddress['address'] : '',
            'user_type' => Role::ADMIN,
            'password' => bcrypt('admin123')
        ];


        $user = User::create($data);

        if ($user !== null) {
            // Attach the role to the user
            $role = Role::where('title', Role::ADMIN)->first();
            $user->roles()->attach($role, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
