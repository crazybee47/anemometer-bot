<?php

namespace App\Meteostation\Application\Service\Meteostation;

use App\Meteostation\Domain\ValueObject\WindDimension\Knots;
use App\Meteostation\Domain\ValueObject\WindDimension\Meters;
use App\Meteostation\Domain\ValueObject\WindDimension\WindDimensionInterface;

class WindSpeedConverter
{

    private const METERS_TO_KNOTS_MULTIPLIER = 1.94384449244;

    public function convert(float $value, WindDimensionInterface $from, ?WindDimensionInterface $to = null): float
    {
        if ($to === null) {
            $to = new Meters();
        }
        if ($from->getId() === $to->getId()) {
            return $value;
        }
        if ($from->getId() === Knots::ID) {
            return $this->convertKnotsToMeters($value);
        }
        return $this->convertMetersToKnots($value);
    }

    private function convertMetersToKnots(float $meters): float
    {
        return round($meters * self::METERS_TO_KNOTS_MULTIPLIER);
    }

    private function convertKnotsToMeters(float $knots): float
    {
        return round($knots / self::METERS_TO_KNOTS_MULTIPLIER);
    }

}
