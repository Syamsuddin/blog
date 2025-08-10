<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user for dashboard access';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if test user already exists
        $existingUser = User::where('email', 'test@example.com')->first();
        
        if ($existingUser) {
            $this->info('Test user already exists!');
            $this->info('Email: test@example.com');
            $this->info('Password: password');
            return;
        }

        // Create test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(), // Mark as verified
        ]);

        $this->info('Test user created successfully!');
        $this->info('Email: test@example.com');
        $this->info('Password: password');
        $this->info('You can now login and access the dashboard.');
    }
}
