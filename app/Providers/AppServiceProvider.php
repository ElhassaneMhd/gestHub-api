<?php

namespace App\Providers;

use App\Models\Demand;
use App\Models\Intern;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
      Relation::morphMap([
        'profile' => Profile::class,
        'demand' => Demand::class,
        'intern' => Intern::class,
    ]);
    }
}
