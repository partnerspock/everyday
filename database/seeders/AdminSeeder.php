<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan Role tersedia (firstOrCreate biar gak null!)
        $partnerRole    = Role::firstOrCreate(['id' => '6']);
        $superAdminRole = Role::firstOrCreate(['id' => '1']);

        // 2. Data Admin dipasangkan langsung dengan ID Role masing-masing
        $admins = [
            [
                'name'         => 'Partner',
                'username'     => 'partner',
                'email'        => 'partner@everyday.test',
                'password'     => Hash::make('admin'),
                'is_confirmed'       => 'confirmed',
                'confirmed_at' => now(),
                'target_role'  => $partnerRole->id,
            ],
            [
                'name'         => 'Admin',
                'username'     => 'admin',
                'email'        => 'admin@everyday.test',
                'password'     => Hash::make('password'),
                'is_confirmed'       => 'confirmed',
                'confirmed_at' => now(),
                'target_role'  => $superAdminRole->id,
            ],
        ];

        foreach ($admins as $data) {
            $roleId = $data['target_role'];
            unset($data['target_role']);
            $createdAdmin = Admin::firstOrCreate(
                ['email' => $data['email']],
                $data
            );

            if (! $createdAdmin->roles()->where('id_role', $roleId)->exists()) {
                $createdAdmin->roles()->attach($roleId);
            }
        }
    }
}