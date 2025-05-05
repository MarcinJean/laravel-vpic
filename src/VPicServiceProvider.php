<?php
namespace MarcinJean\LaravelVPic;

use Illuminate\Support\ServiceProvider;

class VPicServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/create_vpic_vehicles_table.php.stub' =>
            database_path('migrations/' . date('Y_m_d_His') . '_create_vpic_vehicles_table.php'),
        ], 'migrations');
    }

    public function register(): void
    {
        $this->app->singleton(VPicService::class, fn() => new VPicService());
        $this->app->alias(VPicService::class, 'vpic');
    }
}
