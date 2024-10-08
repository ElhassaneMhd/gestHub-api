<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;


class OfferSeeder extends Seeder
{
    /**
     * $faker = Faker::create();

     * Run the database seeds.
     */
    public function run(): void
    {

        $offersData = [
            [
                'title' => 'E-commerce Developer ',
                'description' => 'Develop and maintain features for a growing e-commerce platform. Experience with Laravel and strong PHP skills required.',
                'sector' => 'IT',
                'experience' => 'Expert',
                'skills' => 'PHP, Laravel, MySQL',
                'duration' => 6, // Months
                'type' => 'Onsite',
                'visibility' => 'Visible',
                'status' => 'Normal',
                'city' => 'Casablanca',
                'company' => 'Dev Company',
            ],
            [
                'title' => 'UI/UX Designer',
                'description' => 'Design user interfaces and user experiences for a web application. Experience with Figma or Adobe XD preferred.',
                'sector' => 'IT',
                'experience' => 'Intermediate',
                'skills' => 'UI/UX Design, Figma/Adobe XD, HTML/CSS',
                'duration' => 12, // Months
                'type' => 'Onsite',
                'visibility' => 'Visible',
                'status' => 'Urgent',
                'city' => 'Marrakech',
                'company' => 'Dev Company',
            ],
            [
                'title' => 'Data Analyst ',
                'description' => 'Analyze data to identify trends and insights to support business decisions. Experience with SQL and data visualization tools (e.g., Tableau) a plus.',
                'sector' => 'Data Science',
                'experience' => 'Beginner',
                'skills' => 'SQL, Data Analysis, Data Visualization',
                'duration' => 3, // Months
                'type' => 'Remote',
                'visibility' => 'Visible',
                'status' => 'Normal',
                'city' => 'Agadir',
                'company' => 'Dev Company',
            ],
            [
                'title' => 'Content Marketing Specialist ',
                'description' => 'Create and manage engaging content (blog posts, social media content) to attract and engage website visitors.',
                'sector' => 'Marketing',
                'experience' => 'Intermediate',
                'skills' => 'Content Writing, SEO, Social Media Marketing',
                'duration' => 6, // Months
                'type' => 'Onsite',
                'visibility' => 'Visible',
                'status' => 'Normal',
                'city' => 'Tangier',
                'company' => 'Dev Company',
            ],
            [
                'title' => 'Digital Marketing Manager',
                'description' => 'Develop and execute digital marketing strategies to increase brand awareness and drive website traffic.',
                'sector' => 'Marketing',
                'experience' => 'Expert',
                'skills' => 'SEO, PPC, Social Media Marketing, Analytics',
                'duration' => 12, // Months
                'type' => 'Hybrid',
                'visibility' => 'Visible',
                'status' => 'Normal',
                'city' => 'Rabat',
                'company' => 'Dev Company',
            ],
            [
                'title' => 'Software Quality Assurance Tester ',
                'description' => 'Test software functionality to identify and report bugs. Experience with testing methodologies and automation tools preferred.',
                'sector' => 'IT',
                'experience' => 'Intermediate',
                'skills' => 'Software Testing, QA Automation',
                'duration' => 4, // Months
                'type' => 'Onsite',
                'visibility' => 'Visible',
                'status' => 'Urgent',
                'city' => 'Meknes',
                'company' => 'Dev Company',
            ],
            [
                'title' => 'Customer Service Representative ',
                'description' => 'Provide excellent customer service via phone, email, or chat to resolve customer inquiries and issues.',
                'sector' => 'Customer Service',
                'experience' => 'Intermediate',
                'skills' => 'Customer Service, Communication, Problem-Solving',
                'duration' => 12, // Months
                'type' => 'Hybride',
                'visibility' => 'Visible',
                'status' => 'Normal',
                'city' => 'Tetouan',
                'company' => 'Dev Company',
            ],
        ];
        for ($i = 0; $i < count($offersData); $i++) {
            $offer = new Offer;
            $offer->fill($offersData[$i]);
            $offer->save();
        }
       
        for ($j = 1; $j <= 20; $j++) {
            $application = new Application;
            $application->offer_id = random_int(1,7);
            $application->user_id = random_int(1,2);; // Replace with appropriate user ID
            $application->motivationLetter = 'Motivated and eager to learn and contribute to the ' . $offer->title . ' project.';
            $application->startDate = Carbon::now()->addDays(rand(1, 365));
            $application->endDate = Carbon::now()->addDays(rand($application->startDate->diffInDays(Carbon::now()) + 1, 365)); // Ensure end date is after start date
            $application->save();
        }

    }
}
