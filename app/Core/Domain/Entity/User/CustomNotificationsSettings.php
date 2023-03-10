<?php

namespace App\Core\Domain\Models\User;

class CustomNotificationsSettings
{

    public const METERS_DIMENSION = 'ms';
    public const METERS_ADDITIONAL_DIMENSION = 'm/s';
    public const KNOTS_DIMENSION = 'knots';

    public const AVAILABLE_DIMENSIONS = [
        self::METERS_DIMENSION,
        self::KNOTS_DIMENSION,
        self::METERS_ADDITIONAL_DIMENSION
    ];

    public const DEFAULT_OPERATOR = '>=';

    private string $_operator;
    private string $_dimension;
    private float $_value;

    public function __construct(string $operator, string $dimension, float $value)
    {
        $this->_operator = $operator;
        $this->_dimension = $dimension;
        $this->_value = $value;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->_operator;
    }

    /**
     * @return string
     */
    public function getDimension(): string
    {
        return $this->_dimension;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->_value;
    }

    public static function getHumanDimension(string $dimension): string
    {
        $preparedDimension = $dimension;
        if ($dimension === self::METERS_DIMENSION) {
            return Settings::METERS_REPORT_FORMAT_NAME;
        }
        return $preparedDimension;
    }

}
