<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        Model::unguard();
        Model::shouldBeStrict(!$this->app->isProduction());
        Model::automaticallyEagerLoadRelationships();

        DB::prohibitDestructiveCommands($this->app->isProduction());

        Date::useClass(CarbonImmutable::class);

        if (!$this->app->isLocal()) {
            URL::forceScheme('https');
        }
    }
}
