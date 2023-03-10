<?php

namespace App\Meteostation\Domain\Entity;

use App\Shared\Domain\EntityInterface;

interface SpotInterface extends EntityInterface
{
    public function getName(): string;

    public function getTimezone(): string;
}
