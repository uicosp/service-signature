<?php
/**
 * @author: Yudu <uicosp@gmail.com>
 * @date: 2017/1/10
 */

namespace Uicosp\ServiceSignature;

use Illuminate\Support\ServiceProvider;

class ServiceSignatureProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('service-signature.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config.php', 'service-signature'
        );
    }
}
