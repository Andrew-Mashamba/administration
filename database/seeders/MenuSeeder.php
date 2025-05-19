<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $menus = [
                [
                    'system_id' => 1,
                    'menu_name' => 'Dashboard',
                    'menu_description' => 'Main dashboard overview',
                    'menu_title' => 'Dashboard',
                'menu_number' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'system_id' => 1,
                'menu_name' => 'Users',
                'menu_description' => 'User management section',
                'menu_title' => 'Users',
                'menu_number' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'system_id' => 1,
                'menu_name' => 'Institutions',
                'menu_description' => 'Institutions management',
                'menu_title' => 'Institutions',
                'menu_number' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'system_id' => 1,
                'menu_name' => 'Reports',
                'menu_description' => 'Reports and analytics',
                'menu_title' => 'Reports',
                'menu_number' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
             [
                    'system_id' => 1,
                    'menu_name' => 'Profile',
                    'menu_description' => 'User profile settings',
                    'menu_title' => 'Profile',
                    'menu_number' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            // [
            //     'system_id' => 1,
            //     'menu_name' => 'reports',
            //     'menu_description' => 'System reports and analytics',
            //     'menu_title' => 'Reports',
            //     'menu_number' => 5,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
        ];

        foreach ($menus as $menuData) {
            // Only insert if menu_name doesn't exist for the system_id
            Menu::firstOrCreate(
                [
                    'menu_name' => $menuData['menu_name'],
                    'system_id' => $menuData['system_id'],
                ],
                [
                    'menu_description' => $menuData['menu_description'],
                    'menu_title' => $menuData['menu_title'],
                    'menu_number' => $menuData['menu_number'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}