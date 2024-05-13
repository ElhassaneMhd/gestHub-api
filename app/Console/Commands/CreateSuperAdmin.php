<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:superadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a Super Admin user with the provided details.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $firstName = $this->ask('Enter First Name:');
        $lastName = $this->ask('Enter Last Name:');
        $gender = $this->ask('Enter gender (M/Mme):');
        $validator = Validator::make(['email' => $gender], [
            'email' => 'required|in:M,Mme',
        ]);
         if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return 1; 
        }
        $email = $this->ask('Enter Email:');
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|unique:profiles|email',
        ]);
        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return 1; 
        }
        $password = $this->secret('Enter Password (will not be shown):');
        $phone = $this->ask('Enter Phone Number (optional):');

        $profile = Profile::create([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'gender' => $gender,
            'password' => Hash::make($password),
            'phone' => $phone,
        ]);
        $profile->assignRole('super-admin');
        $admin = new Admin;
        $admin->profile_id = $profile->id;
        $admin->save();

        $this->info("Super Admin created successfully!");
        $this->info("Email: $email"); // Display the created user's email for reference

        return 0; // Indicate success
    }
}
