<?php

namespace App\Shared\Infrastructure\Providers;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\Bus\CommandBus;
use App\Shared\Infrastructure\Bus\QueryBus;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(QueryBusInterface::class, QueryBus::class);
        $this->app->singleton(CommandBusInterface::class, CommandBus::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
