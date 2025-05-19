<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\NavigationMenu;
use App\Livewire\Dashboard;
use App\Livewire\SideBar;
use App\Livewire\Users;
use App\Livewire\Settings;
use App\Livewire\Profile\Profile;

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
        Livewire::component('navigation-menu', NavigationMenu::class);
        Livewire::component('dashboard', Dashboard::class);
        Livewire::component('side-bar', SideBar::class);
        Livewire::component('users', Users::class);
        Livewire::component('settings', Settings::class);
        Livewire::component('profile.profile', Profile::class);

        \Livewire\Livewire::setUpdateRoute(function ($handle) {
            return \Illuminate\Support\Facades\Route::post('/livewire/update', $handle);
        });
    }
}
