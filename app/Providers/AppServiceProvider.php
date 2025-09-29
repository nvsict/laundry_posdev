<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting; // ✅ Important

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $settings = Setting::all()->pluck('value', 'key');
            $view->with('storeName', $settings['store_name'] ?? config('app.name'));
        });
    }
}
