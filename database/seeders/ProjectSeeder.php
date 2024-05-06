<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
        $projects = [
            [
                'subject' => 'E-commerce Website Redesign',
                'description' => 'Revamped the existing online store to improve user experience, enhance product visibility, and increase conversion rates. Implemented features include a new search bar, improved product filters, and a streamlined checkout process.',
                'status' => 'In Progress',
                'priority' => 'High',
            ],
            [
                'subject' => 'Marketing Automation Implementation',
                'description' => 'Integrated a marketing automation platform to improve email marketing campaigns, automate lead nurturing workflows, and personalize customer journeys. This resulted in increased lead generation and conversion rates.',
                'status' => 'In Progress',
                'priority' => 'Medium',
            ],
            [
                'subject' => 'Mobile App Development for Language Learning',
                'description' => 'Developed a mobile application that allows users to learn languages at their own pace. The app features interactive lessons, gamification elements, and personalized learning paths.',
                'status' => 'In Progress',
                'priority' => 'High',
            ],
            [
                'subject' => 'Network Security Audit and Optimization',
                'description' => 'Conducted a comprehensive security audit of the company network to identify vulnerabilities and recommend remediation measures. The audit also evaluated network performance and suggested optimization strategies.',
                'status' => 'In Progress',
                'priority' => 'High',
            ],
            [
                'subject' => 'Content Management System (CMS) Migration',
                'description' => 'Migrated the existing website content to a new CMS platform. The migration process included careful planning, data validation, and content restructuring to ensure a smooth transition.',
                'status' => 'In Progress',
                'priority' => 'Medium',
            ],
            [
                'subject' => 'Customer Relationship Management (CRM) System Upgrade',
                'description' => 'Upgraded the existing CRM system to the latest version to benefit from security enhancements, new features, and improved performance. Data migration from the previous version was carefully orchestrated.',
                'status' => 'In Progress',
                'priority' => 'Medium',
            ],
            [
                'subject' => 'Data Analytics Platform Development',
                'description' => 'Developed a data analytics platform to collect, analyze, and visualize business data. This platform empowers decision-makers with insights to improve marketing campaigns, optimize resource allocation, and track key performance indicators (KPIs).',
                'status' => 'In Progress',
                'priority' => 'High',
            ],
            [
                'subject' => 'Cloud Storage Migration',
                'description' => 'Migrated a large volume of enterprise data to a cloud storage platform, enhancing scalability, accessibility, and cost-efficiency. Data security and access control measures were implemented during the migration process.',
                'status' => 'In Progress',
                'priority' => 'High',
            ],
            [
                'subject' => 'Website Accessibility Audit and Remediation',
                'description' => 'Performed an accessibility audit of the company website to identify and address barriers that might prevent users with disabilities from accessing information and interacting with the website effectively.',
                'status' => 'In Progress',
                'priority' => 'Medium',
            ],
            [
                'subject' => 'Software Development Life Cycle (SDLC) Implementation',
                'description' => 'Implemented a standardized SDLC process within the development team to improve project planning, development workflow, quality assurance, and project management. This resulted in more efficient project delivery and reduced risks.',
                'status' => 'In Progress',
                'priority' => 'High',
            ],
            ];
        $tasksData = [
            [
                'title' => 'Requirement Gathering',
                'description' => 'Meet with stakeholders to gather project requirements and define user stories.',
                'priority' => 'High',
                'status' => 'Done',
            ],
            [
                'title' => 'Task Breakdown and Estimation',
                'description' => 'Break down the project into smaller tasks and estimate the time and effort required for each.',
                'priority' => 'Medium',
                'status' => 'To Do',
            ],
            [
                'title' => 'Communication Plan Development',
                'description' => 'Define communication channels and frequency for project updates and collaboration.',
                'priority' => 'High',
                'status' => 'Done',
            ],
            [
                'title' => 'Version Control Setup',
                'description' => 'Set up a version control system (e.g., Git) to track changes to project files.',
                'priority' => 'Low',
                'status' => 'In Progress',
            ],
            [
                'title' => 'Initial Test Plan Creation',
                'description' => 'Outline a basic test plan to identify areas for testing throughout the project.',
                'priority' => 'Medium',
                'status' => 'To Do',
            ],
        ];
        foreach ($projects as $projectData) {
            $project = new Project;
            $project->subject = $projectData['subject'];
            $project->description = $projectData['description'];
            $project->startDate = Carbon::now()->subDays(rand(1, 365));
            $project->endDate = Carbon::now()->addDays(rand(1, 365));
            $project->status = $projectData['status'];
            $project->priority = $projectData['priority'];
            $project->supervisor_id = random_int(1, 5); // replace with appropriate supervisor ID
            $project->intern_id = random_int(1, 5);; // replace with appropriate intern ID
            $project->save();
            // Attach the intern to the project with a pivot table
            $project->interns()->attach($project->intern_id);

            foreach ($tasksData as $taskData) {
                $task = new Task;
                $task->title = $taskData['title'];
                $task->description = $taskData['description'];
                $task->dueDate = Carbon::now()->addDays(rand(1, 365));
                $task->priority = $taskData['priority'];
                $task->status = $taskData['status'];
                $task->intern_id = $project->intern_id;
                $task->project_id = $project->id; // associate the task with the project
                $task->save();
            }
        }
      }
}
