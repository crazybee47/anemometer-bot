<?php

namespace App\Core\Domain\ValueObject\User\Settings;

use App\Meteostation\Domain\ValueObject\WindDimension\WindDimensionInterface;

class CustomFrequencyRule
{
    private string $operator;

    private float $value;

    private WindDimensionInterface $dimension;

    public function __construct(string $operator, float $value, WindDimensionInterface $dimension)
    {
        $this->operator = $operator;
        $this->value = $value;
        $this->dimension = $dimension;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return WindDimensionInterface
     */
    public function getDimension(): WindDimensionInterface
    {
        return $this->dimension;
    }
}
