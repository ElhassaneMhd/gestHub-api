<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\File;
use App\Models\Intern;
use App\Models\Offer;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Task;
use App\Observers\applicationObserser;
use App\Observers\fileObserser;
use App\Observers\offerObserser;
use App\Observers\profileObserser;
use App\Observers\projectObserser;
use App\Observers\taskObserser;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Profiler\Profiler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void{
      Relation::morphMap([
        'profile' => Profile::class,
        'application' => Application::class,
        'intern' => Intern::class,
        ]);
        Profile::observe(profileObserser::class);
        Offer::observe(offerObserser::class);
        Application::observe(applicationObserser::class);
        Task::observe(taskObserser::class);
        Project::observe(projectObserser::class);
        File::observe(fileObserser::class);
    }
}
