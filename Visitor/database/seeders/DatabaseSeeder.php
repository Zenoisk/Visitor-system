<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $enableDemoUsers = filter_var(env('ENABLE_DEMO_USERS', false), FILTER_VALIDATE_BOOLEAN);

        // Only run if enabled, or if in local/testing environment
        if ($enableDemoUsers || app()->environment('local', 'testing')) {
            $adminEmail = env('DEMO_ADMIN_EMAIL', 'admin@airod.test');
            $adminUsername = env('DEMO_ADMIN_USERNAME', 'admin');
            $adminPassword = env('DEMO_ADMIN_PASSWORD', 'password');

            $securityEmail = env('DEMO_SECURITY_EMAIL', 'security@airod.test');
            $securityUsername = env('DEMO_SECURITY_USERNAME', 'security');
            $securityPassword = env('DEMO_SECURITY_PASSWORD', 'password');

            User::updateOrCreate([
                'email' => $adminEmail,
            ], [
                'name' => 'AIROD Admin',
                'username' => $adminUsername,
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
            ]);

            User::updateOrCreate([
                'email' => $securityEmail,
            ], [
                'name' => 'Security Counter',
                'username' => $securityUsername,
                'password' => Hash::make($securityPassword),
                'role' => 'security',
            ]);
        }
    }
}
