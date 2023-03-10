<?php

namespace App\Meteostation\Application\Service\Meteostation\Formatter;

use App\Meteostation\Application\Service\Meteostation\WindSpeedConverter;
use App\Meteostation\Domain\Entity\Spot;
use App\Meteostation\Domain\ValueObject\DataInterface;
use App\Meteostation\Domain\ValueObject\WindDimension\Meters;
use App\Meteostation\Domain\ValueObject\WindSpeed;
use Carbon\CarbonInterface;

class MeterFormatter implements DataInterface
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
        $minWindInMeters = $converter->convert($this->getMinWindSpeed()->getValue(), $this->getMinWindSpeed()->getDimension(), new Meters());
        $maxWindInMeters = $converter->convert($this->getMaxWindSpeed()->getValue(), $this->getMaxWindSpeed()->getDimension(), new Meters());

        return "min: {$minWindInMeters} (m/s)\nmax: {$maxWindInMeters} (m/s)";
    }

}
