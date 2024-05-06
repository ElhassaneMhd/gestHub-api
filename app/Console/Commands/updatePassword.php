<?php

namespace App\Console\Commands;

use App\Models\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Validator;

class updatePassword extends Command
{
    protected $signature = 'updatePassword';

    protected $description = 'Command description';


    public function handle()
     {
        $email = $this->ask('Enter Email:');

         $validator = Validator::make(['email' => $email], [
            'email' => 'required|exists:profiles|email',
        ]);
        if ($validator->fails()) {
            $this->error($validator->messages()->first());
            return 1; 
        }
        $profile = Profile::where("email", '=', $email);
        $password = $this->secret('Enter Password (will not be shown):');
        $profile->password = Hash::make($password) ;
        $profile->save();

        $this->info("password updated succefully");
        
        return 0;
    }
}
