<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        FilamentAsset::register([
            Js::make('reminder-duration', 'js/reminder-duration.js')
                ->loadedOnRequest()
                ->async(),
        ]);
    }
}