<?php

namespace App\Meteostation\Application\Service\Meteostation;

interface MeteostationServiceInterface
{
    public function getSpotData(string $spotId): object;
}
