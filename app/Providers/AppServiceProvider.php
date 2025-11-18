<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // 💡 Import View Facade
use App\View\Composers\SidebarComposer; // 💡 Import the Composer Class (assuming you created it)

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
        View::composer('layouts.main_store_layout', SidebarComposer::class);
    }
}