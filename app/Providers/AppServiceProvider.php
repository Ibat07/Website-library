<?php

namespace App\Providers;

use Exception;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @throws Exception
     */
    public function register(): void
    {
        Panel::make()
            ->id('admin') // Assign ID for the admin panel
            ->path('/admin')
            ->login();

        Panel::make()
            ->id('user') // Assign ID for the user panel
            ->path('/user')
            ->login()
            ->register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
    }
}
