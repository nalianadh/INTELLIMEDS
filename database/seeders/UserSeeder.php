<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'u_name'     => 'Main Store Admin',
                'u_username' => 'mainstore',
                'u_password' => Hash::make('password123'),
                'u_email'    => 'mainstore@example.com',
                'u_phone'    => '0123456789',
                'u_role'     => 'main_store',
                'u_unit'     => 'Main Store',
                'grn_id'     => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'u_name'     => 'Sub Department User',
                'u_username' => 'subdept',
                'u_password' => Hash::make('password456'),
                'u_email'    => 'subdept@example.com',
                'u_phone'    => '0198765432',
                'u_role'     => 'sub_department',
                'u_unit'     => 'Pharmacy Department',
                'grn_id'     => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
