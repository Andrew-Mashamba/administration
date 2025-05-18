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
                'menu_name' => 'dashboard',
                'menu_description' => 'Main dashboard overview',
                'menu_title' => 'Dashboard',
                'menu_number' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'system_id' => 1,
                'menu_name' => 'users',
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
            // [
            //     'system_id' => 1,
            //     'menu_name' => 'settings',
            //     'menu_description' => 'System configuration settings',
            //     'menu_title' => 'Settings',
            //     'menu_number' => 4,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
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

        DB::table('menus')->insert($menus);
    }
} 