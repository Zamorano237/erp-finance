<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ExpenseDemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@erp.test'],
            [
                'name' => 'Admin ERP',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'finance@erp.test'],
            [
                'name' => 'Finance ERP',
                'password' => Hash::make('password'),
                'role' => 'finance',
            ]
        );

        User::updateOrCreate(
            ['email' => 'validator@erp.test'],
            [
                'name' => 'Validator ERP',
                'password' => Hash::make('password'),
                'role' => 'validator',
            ]
        );

        User::updateOrCreate(
            ['email' => 'reader@erp.test'],
            [
                'name' => 'Reader ERP',
                'password' => Hash::make('password'),
                'role' => 'reader',
            ]
        );
    }
}