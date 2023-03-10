<?php

namespace App\Meteostation\Domain\ValueObject;

use App\Meteostation\Application\Service\Meteostation\WindSpeedConverter;
use App\Meteostation\Domain\ValueObject\WindDimension\Knots;
use App\Meteostation\Domain\ValueObject\WindDimension\Meters;
use Carbon\CarbonInterface;

class MeteostationData implements DataInterface
{

    private const DATE_FORMAT = 'd.m.Y H:i';

    private CarbonInterface $_date;

    private WindSpeed $_minWindSpeed;

    private WindSpeed $_maxWindSpeed;

    private WindSpeed $_avgWindSpeed;

    private ?string $_windDirection;

    private ?float $_temperature;

    public function __construct(
        CarbonInterface $date,
        WindSpeed       $minWindSpeed,
        WindSpeed       $maxWindSpeed,
        WindSpeed       $avgWindSpeed,
        ?string         $windDirection,
        ?float          $temperature
    )
    {
        $this->_date = $date;
        $this->_minWindSpeed = $minWindSpeed;
        $this->_maxWindSpeed = $maxWindSpeed;
        $this->_avgWindSpeed = $avgWindSpeed;
        $this->_windDirection = $windDirection;
        $this->_temperature = $temperature;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): CarbonInterface
    {
        return $this->_date;
    }

    public function getMinWindSpeed(): WindSpeedInterface
    {
        return $this->_minWindSpeed;
    }

    public function getMaxWindSpeed(): WindSpeedInterface
    {
        return $this->_maxWindSpeed;
    }

    public function getAvgWindSpeed(): WindSpeedInterface
    {
        return $this->_avgWindSpeed;
    }

    /**
     * @return string
     */
    public function getWindDirection(): ?string
    {
        return $this->_windDirection;
    }

    /**
     * @return float
     */
    public function getTemperature(): ?float
    {
        return $this->_temperature;
    }

    public function __toString()
    {
        /** @var WindSpeedConverter $converter */
        $converter = app(WindSpeedConverter::class);
        $minWindInMeters = $converter->convert($this->getMinWindSpeed()->getValue(), $this->getMinWindSpeed()->getDimension(), new Meters());
        $avgWindInMeters = $converter->convert($this->getAvgWindSpeed()->getValue(), $this->getAvgWindSpeed()->getDimension(), new Meters());
        $maxWindInMeters = $converter->convert($this->getMaxWindSpeed()->getValue(), $this->getMaxWindSpeed()->getDimension(), new Meters());
        $minWindInKnots = $converter->convert($this->getMinWindSpeed()->getValue(), $this->getMinWindSpeed()->getDimension(), new Knots());
        $avgWindInKnots = $converter->convert($this->getAvgWindSpeed()->getValue(), $this->getAvgWindSpeed()->getDimension(), new Knots());
        $maxWindInKnots = $converter->convert($this->getMaxWindSpeed()->getValue(), $this->getMaxWindSpeed()->getDimension(), new Knots());

        return "Скорость ветра min: {$minWindInMeters} (м/с)\nСкорость ветра ср: {$avgWindInMeters} (м/с)\nСкорость ветра max: {$maxWindInMeters} (м/с)\n\nСкорость ветра min: {$minWindInKnots} (узлов)\nСкорость ветра ср: {$avgWindInKnots} (узлов)\nСкорость ветра max: {$maxWindInKnots} (узлов)\n\nНаправление ветра: {$this->getWindDirection()}\nТемпература воздуха, °С: {$this->getTemperature()}";
    }

}
