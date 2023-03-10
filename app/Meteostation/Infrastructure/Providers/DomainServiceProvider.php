<?php

namespace App\Meteostation\Infrastructure\Providers;

use App\Meteostation\Application\Adapters\MeteostationAdapterInterface;
use App\Meteostation\Application\Service\Meteostation\MeteostationService;
use App\Meteostation\Application\Service\Meteostation\MeteostationServiceInterface;
use App\Meteostation\Domain\Repository\SpotRepositoryInterface;
use App\Meteostation\Infrastructure\Adapters\Meteostation\MeteostationAdapter;
use App\Meteostation\Infrastructure\Repository\SpotRepository;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(MeteostationAdapterInterface::class, MeteostationAdapter::class);

        $this->app->singleton(SpotRepositoryInterface::class, SpotRepository::class);

        $this->app->singleton(MeteostationServiceInterface::class, MeteostationService::class);
    }

}
