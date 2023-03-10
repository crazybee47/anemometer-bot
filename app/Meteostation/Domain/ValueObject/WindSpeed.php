<?php

namespace App\Meteostation\Domain\ValueObject;

use App\Meteostation\Domain\ValueObject\WindDimension\Knots;
use App\Meteostation\Domain\ValueObject\WindDimension\Meters;
use App\Meteostation\Domain\ValueObject\WindDimension\WindDimensionInterface;

class WindSpeed implements WindSpeedInterface
{
    private WindDimensionInterface $dimension;

    private float $value;

    public function __construct(float $value, WindDimensionInterface $dimension)
    {
        $this->value = $value;
        $this->dimension = $dimension;
    }

    public static function buildKnots(float $value): self
    {
        return new self($value, new Knots());
    }

    public static function buildMeters(float $value): self
    {
        return new self($value, new Meters());
    }

    public function getDimension(): WindDimensionInterface
    {
        return $this->dimension;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}
