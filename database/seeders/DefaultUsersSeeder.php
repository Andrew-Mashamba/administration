<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DefaultUsersSeeder extends Seeder
{
    public function run(): void
    {
        $managerEmail = config('sacco.manager_email');
        $itEmail = config('sacco.it_email');

        if ($managerEmail) {
            DB::table('users')->insert([
                'name' => 'SACCOS Manager',
                'email' => $managerEmail,
                'password' => Hash::make('1234567890'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        if ($itEmail) {
            DB::table('users')->insert([
                'name' => 'IT Administrator',
                'email' => $itEmail,
                'password' => Hash::make('1234567891'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
} 