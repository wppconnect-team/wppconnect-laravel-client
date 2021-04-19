<?php

declare(strict_types=1);

namespace WPPConnectTeam\Wppconnect;

use Illuminate\Support\ServiceProvider;

class WppconnectServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
	    __DIR__.'/config/wppconnect.php' => config_path('wppconnect.php'),
	]);
    }

    /**
     * Register any application services.;
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(RequestInterface::class, Wppconnect::class);
    }
}
