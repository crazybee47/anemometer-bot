<?php

namespace App\Meteostation\Infrastructure\Repository;

use App\Meteostation\Domain\Entity\Spot;
use App\Meteostation\Domain\Entity\SpotInterface;
use App\Meteostation\Domain\Repository\SpotRepositoryInterface;

class SpotRepository implements SpotRepositoryInterface
{
    public function find(string $id): ?SpotInterface
    {
        return Spot::where('external_id', $id)->first();
    }
}
