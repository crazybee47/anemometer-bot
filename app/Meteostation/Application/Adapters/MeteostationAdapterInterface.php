<?php

namespace App\Meteostation\Application\Adapters;

use App\Meteostation\Domain\Entity\Spot;
use App\Meteostation\Domain\ValueObject\DataInterface;

interface MeteostationAdapterInterface
{
    public function getSpotData(string $spotId): DataInterface;
}
