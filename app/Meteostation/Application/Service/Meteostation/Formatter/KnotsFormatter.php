<?php

namespace App\Meteostation\Application\Service\Meteostation\Formatter;

use App\Meteostation\Application\Service\Meteostation\WindSpeedConverter;
use App\Meteostation\Domain\Entity\Spot;
use App\Meteostation\Domain\ValueObject\DataInterface;
use App\Meteostation\Domain\ValueObject\WindDimension\Knots;
use App\Meteostation\Domain\ValueObject\WindSpeed;
use Carbon\CarbonInterface;

class KnotsFormatter implements DataInterface
{

    private DataInterface $_parserResult;

    public function __construct(DataInterface $parserResult)
    {
        $this->_parserResult = $parserResult;
    }

    public function getDate(): CarbonInterface
    {
        return $this->_parserResult->getDate();
    }

    public function getMinWindSpeed(): WindSpeed
    {
        return $this->_parserResult->getMinWindSpeed();
    }

    public function getMaxWindSpeed(): WindSpeed
    {
        return $this->_parserResult->getMaxWindSpeed();
    }

    public function getAvgWindSpeed(): WindSpeed
    {
        return $this->_parserResult->getAvgWindSpeed();
    }

    /**
     * @return string
     */
    public function getWindDirection(): ?string
    {
        return $this->_parserResult->getWindDirection();
    }

    /**
     * @return float
     */
    public function getTemperature(): ?float
    {
        return $this->_parserResult->getTemperature();
    }

    public function getSpot(): Spot
    {
        return $this->_parserResult->getSpot();
    }

    public function __toString(): string
    {
        /** @var WindSpeedConverter $converter */
        $converter = app(WindSpeedConverter::class);
        $minWindInKnots = $converter->convert($this->getMinWindSpeed()->getValue(), $this->getMinWindSpeed()->getDimension(), new Knots());
        $maxWindInKnots = $converter->convert($this->getMaxWindSpeed()->getValue(), $this->getMaxWindSpeed()->getDimension(), new Knots());

        return "min: {$minWindInKnots} (knots)\nmax: {$maxWindInKnots} (knots)";
    }

}
