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

// $faker = Faker::create();

// $roles = ['user','intern', 'supervisor', 'admin', 'super-admin'];


// foreach ($roles as $role) {
//     for ($i = 0; $i < 5; $i++) {
//         $profile = new Profile;
//         $profile->firstName = $faker->firstName;
//         $profile->lastName = $faker->lastName;
//         $profile->email = $profile->firstName.'@gmail.com';
//         $profile->password = Hash::make($profile->firstName.'123');
//         $profile->phone = $faker->phoneNumber;
//         $profile->gender = $faker->randomElement(['M', "Mme"]);
//         $profile->assignRole($role);
//         $profile->save();

//         switch ($role) {
//             case 'user':
//                 DB::table('users')->insert([
//                     'profile_id' => $profile->id,
//                     'academicLevel' => 'Bac+2',
//                     'establishment' => 'Ofppt',
//                 ]);
//                 break;
//             case 'supervisor':
//                 DB::table('supervisors')->insert([
//                     'profile_id' => $profile->id,
//                 ]);
//                 break;
//             case 'admin':
//             case 'super-admin':
//                 DB::table('admins')->insert([
//                     'profile_id' => $profile->id,
//                 ]);
//                 break;
//             case 'intern':
//                 DB::table('interns')->insert([
//                     'profile_id' => $profile->id,
//                     'academicLevel' => 'Bac+2',
//                     'establishment' => 'Ofppt',
//                     'specialty' => 'Développement',
//                     'startDate' => $faker->dateTimeBetween('-1 years', '+1 years')->format('Y-m-d H:i:s'),
//                     'endDate' => $faker->dateTimeBetween('+1 years', '+2 years')->format('Y-m-d H:i:s'),
//                 ]);
//                 break;
//         }
//     }
//     }

         
    }
}
