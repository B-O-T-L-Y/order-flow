<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(50)->create();

        $admin = User::factory()->admin()->create();
        $adminPassword = UserFactory::generatedPassword();

        $this->command->info('Admin User created');
        $this->command->info('Email: ' . $admin->email);
        $this->command->info('Password: ' . $adminPassword);
    }
}
