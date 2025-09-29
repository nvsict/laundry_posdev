<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Get all settings (API).
     */
    public function index()
    {
        // Return all settings as a key-value pair for easy use on the frontend
        return Setting::all()->pluck('value', 'key');
    }

    /**
     * Update all settings (API).
     */
    public function update(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json(['message' => 'Settings updated successfully.']);
    }

    /**
     * Show settings page (web).
     */
    public function edit()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('settings', compact('settings'));
    }

    /**
     * Share store_name globally to all views.
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $settings = Setting::all()->pluck('value', 'key');
            $view->with('storeName', $settings['store_name'] ?? config('app.name'));
        });
    }
}
