<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        URL::forceRootUrl(env('FORCE_ROOT_URL', 'https://twp.capitalplace.co.id/webtenant'));
		// https dipaksa kecuali diakses dari localhost
		if (!in_array(request()->getHost(), ['localhost', '127.0.0.1'])) {
			URL::forceScheme('https');
		}
    }
}
