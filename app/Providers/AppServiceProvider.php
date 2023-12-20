<?php
namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data = [], $message = '', $settings = []) {
            return Response::make([
                'data' => $data,
                'message' => $message,
                'settings' => $settings
            ], 200);
        });

        Response::macro('failed', function ($error = [], $httpCode = 422, $settings = []) {
            return Response::make([
                'errors' => $error,
                'settings' => $settings
            ], $httpCode);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
