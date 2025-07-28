<?php

namespace Database\Seeders;

use App\Models\ProjectStatus;
use Illuminate\Database\Seeder;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Not Started',
                'color' => '#6c757d', // Gray
            ],
            [
                'name' => 'In Progress',
                'color' => '#007bff', // Blue
            ],
            [
                'name' => 'On Hold',
                'color' => '#ffc107', // Yellow
            ],
            [
                'name' => 'Completed',
                'color' => '#28a745', // Green
            ],
            [
                'name' => 'Cancelled',
                'color' => '#dc3545', // Red
            ],
        ];

        foreach ($statuses as $status) {
            ProjectStatus::create($status);
        }
    }
}