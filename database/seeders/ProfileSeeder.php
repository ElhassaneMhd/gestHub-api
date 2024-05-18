<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $profile = new Profile;
        $profile->firstName = 'Hassan';
        $profile->lastName = 'Mehdioui';
        $profile->email = 'Hassan.Mhd@gmail.com';
        $profile->password = Hash::make('Hassan123');
        $profile->phone = '0675178574';
        $profile->gender = 'M';
        $profile->assignRole('super-admin');
        $profile->save();
        DB::table('admins')->insert([
            'profile_id' => $profile->id,
        ]);


        $users =[
            ['firstName'=>'Ahmed','lastName'=>'Idrissi','phoneNumber'=>'0655441122','gender'=>'M','academicLevel'=>'Bac+3',"establishment"=>'FSR'],
            ['firstName'=>'Mehdi','lastName'=>'Amir','phoneNumber'=>'0652541928','gender'=>'M','academicLevel'=>'Bac+5',"establishment"=>'Ensa']
        ];
        foreach ($users as $user) {
            $profile = new Profile;
            $profile->firstName = $user["firstName"];
            $profile->lastName = $user["lastName"];
            $profile->email = $profile->firstName.'.'.$profile->lastName.'@gmail.com';
            $profile->password = Hash::make($profile->firstName.'123');
            $profile->phone = $user['phoneNumber'];
            $profile->gender = $user['gender'];
            $profile->assignRole('user');
            $profile->save();
                DB::table('users')->insert([
                    'profile_id' => $profile->id,
                    'academicLevel' => $user['academicLevel'],
                    'establishment' => $user['establishment'],
                ]);

        }
        $interns =[
            ['firstName'=>'Walid','lastName'=>'Zakan','phoneNumber'=>'0675175874','gender'=>'M','academicLevel'=>'Bac+2',"establishment"=>'Ofppt'],
            ['firstName'=>'Elhassane','lastName'=>'Mehdioui','phoneNumber'=>'0675175874','gender'=>'M','academicLevel'=>'Bac+2',"establishment"=>'Ofppt']
        ];
         foreach ($interns as $intern) {
            $profile = new Profile;
            $profile->firstName = $intern["firstName"];
            $profile->lastName = $intern["lastName"];
            $profile->email = $profile->firstName.'.'.$profile->lastName.'@gmail.com';
            $profile->password = Hash::make($profile->firstName.'123');
            $profile->phone = $intern['phoneNumber'];
            $profile->gender = $intern['gender'];
            $profile->assignRole('intern');
            $profile->save();
                DB::table('interns')->insert([
                    'profile_id' => $profile->id,
                    'academicLevel' => $intern['academicLevel'],
                    'establishment' => $intern['establishment'],
                    'specialty' => 'Développement',
                    'startDate' => '2024-03-18',
                    'endDate' => '2024-06-28'

                ]);

        }

        $admins =[
            ['firstName'=>'Bahae Eddine','lastName'=>'Halim','phoneNumber'=>'0666666666','gender'=>'M'],
            ['firstName'=>'admin','lastName'=>'2','phoneNumber'=>'0600666666','gender'=>'M'],
        ];
         foreach ($admins as $admin) {
            $profile = new Profile;
            $profile->firstName = $admin["firstName"];
            $profile->lastName = $admin["lastName"];
            $profile->email = $profile->firstName.'.'.$profile->lastName.'@gmail.com';
            $profile->password = Hash::make($profile->firstName.'123');
            $profile->phone = $admin['phoneNumber'];
            $profile->gender = $admin['gender'];
            $profile->assignRole('admin');
            $profile->save();
                DB::table('admins')->insert([
                    'profile_id' => $profile->id,
                ]);
        }

        $supervisors =[
            ['firstName'=>'Bahae Eddine','lastName'=>'Halim','phoneNumber'=>'0665866666','gender'=>'M'],
            ['firstName'=>'sup','lastName'=>'2','phoneNumber'=>'0666682666','gender'=>'M'],
        ];
         foreach ($supervisors as $supervisor) {
            $profile = new Profile;
            $profile->firstName = $supervisor["firstName"];
            $profile->lastName = $supervisor["lastName"];
            $profile->email = $profile->firstName.'.'.$profile->lastName.'2@gmail.com';
            $profile->password = Hash::make($profile->firstName.'123');
            $profile->phone = $supervisor['phoneNumber'];
            $profile->gender = $supervisor['gender'];
            $profile->assignRole('supervisor');
            $profile->save();
                DB::table('supervisors')->insert([
                    'profile_id' => $profile->id,
                ]);
        }

         
    }
}
