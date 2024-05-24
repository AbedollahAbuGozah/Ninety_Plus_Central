<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\services\NinetyPlusCentral;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('ninety_plus_central',function(){
            return new NinetyPlusCentral();
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
