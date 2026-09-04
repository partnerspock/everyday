<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gnote;
use App\Models\Admin;

class GeneralNotesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first() ?? Admin::create([
            'name'         => 'Super Admin',
            'username'     => 'admin',
            'email'        => 'admin@everyday.test',
            'password'     => 'password',
            'status'       => 'confirmed',
            'confirmed_at' => now(),
        ]);

        $notes = [
            [
                'created_user' => $admin->id,
                'title_notes'  => 'Master System Architecture',
                'description'  => 'Standard guidelines for system modules, route naming conventions, and database migrations.',
                'password'     => null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'created_user' => $admin->id,
                'title_notes'  => 'Confidential Server Access',
                'description'  => 'SSH keys, database root credentials, and production environment secrets.',
                'password'     => 'secret123',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        foreach ($notes as $noteData) {
            Gnote::updateOrCreate(
                [
                    'created_user' => $noteData['created_user'],
                    'title_notes'  => $noteData['title_notes'],
                ],
                $noteData
            );
        }
    }
}