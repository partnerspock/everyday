<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Space;
use App\Models\Admin;

class SpaceSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = Admin::where('username', 'admin')->value('id') ?? 2;

        $spacesData = [
            ['title_spaces' => 'Board of Directors'],
            ['title_spaces' => 'HC - Management'],
            ['title_spaces' => 'HC - Recruitment'],
            ['title_spaces' => 'IT - Infrastructure'],
            ['title_spaces' => 'IT - Development'],
            ['title_spaces' => 'IT - Support'],
        ];

        foreach ($spacesData as $sData) {
            $space = Space::firstOrCreate(
                [
                    'title_spaces' => $sData['title_spaces'],
                    'created_user' => $adminId,
                ]
            );

            for ($i = 1; $i <= 10; $i++) {
                $space->subNote()->firstOrCreate(
                    [
                        'title_notes' => "Meeting Minutes #{$i} - " . $space->title_spaces,
                    ],
                    [
                        'description' => "Detailed summary and action plan for meeting item #{$i}.",
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]
                );
            }

            $statuses = ['not_ticked', 'ticked'];

            for ($j = 1; $j <= 10; $j++) {
                $space->subReminder()->firstOrCreate(
                    [
                        'row_title' => "Task Reminder #{$j} - " . $space->title_spaces,
                    ],
                    [
                        'row_description' => "Scheduled tasks and operational checklist #{$j}.",
                        'row_status'      => $statuses[$j % 2], // Rotasi antara not_ticked & ticked
                        'row_order'       => $j,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]
                );
            }

            $subProjects = [
                [
                    'pro_title'       => 'System Modernization 2026',
                    'pro_description' => 'Overhauling UI/UX and infrastructure to modern standards.',
                    'pro_status'      => 'not_started',
                ],
                [
                    'pro_title'       => 'Operational Audit & Security',
                    'pro_description' => 'Comprehensive compliance check and access control audit.',
                    'pro_status'      => 'in_progress',
                ],
                [
                    'pro_title'       => 'Q1 Team Onboarding Kit',
                    'pro_description' => 'Updating documentation, templates, and workflow charts.',
                    'pro_status'      => 'completed',
                ],
            ];

            foreach ($subProjects as $subProjectData) {
                $space->subProject()->firstOrCreate(
                    ['pro_title' => $subProjectData['pro_title']],
                    [
                        'pro_description' => $subProjectData['pro_description'],
                        'pro_status'      => $subProjectData['pro_status'],
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]
                );
            }
        }
    }
}