<?php

namespace App\Core\Infrastructure\Providers;

use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Domain\Service\NotifierInterface;
use App\Core\DomainNew\Service\SettingsService;
use App\Core\DomainNew\Service\SettingsServiceInterface;
use App\Core\Infrastructure\Repository\UserRepository;
use App\Core\Infrastructure\Service\TelegramNotifier;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(NotifierInterface::class, TelegramNotifier::class);
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(SettingsServiceInterface::class, SettingsService::class);
    }

}
