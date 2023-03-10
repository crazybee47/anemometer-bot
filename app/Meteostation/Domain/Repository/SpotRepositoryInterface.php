<?php

namespace App\Meteostation\Domain\Repository;

use App\Meteostation\Domain\Entity\SpotInterface;

interface SpotRepositoryInterface
{
    public function find(string $id): ?SpotInterface;
}
