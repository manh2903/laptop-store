<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- 1. THÊM DÒNG NÀY Ở TRÊN CÙNG


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
        
        // <--- 2. THÊM ĐOẠN CODE NÀY VÀO HÀM BOOT
        if($this->app->environment('production') || !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') {
            URL::forceScheme('https');
        }
    }
}
