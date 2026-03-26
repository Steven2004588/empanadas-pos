<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function boot(UrlGenerator $url): void
    {
        if (str_starts_with(config('app.url'), 'https')) {
            $url->forceScheme('https');
        }

        $appUrl = config('app.url');
        if ($appUrl) {
            $url->forceRootUrl($appUrl);
        }
    }
}