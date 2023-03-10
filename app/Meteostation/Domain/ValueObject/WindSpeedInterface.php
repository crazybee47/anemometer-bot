<?php

namespace App\Meteostation\Domain\ValueObject;

use App\Meteostation\Domain\ValueObject\WindDimension\WindDimensionInterface;

interface WindSpeedInterface
{
    public function getDimension(): WindDimensionInterface;

    public function getValue(): float;
}
