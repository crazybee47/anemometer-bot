<?php

namespace App\Meteostation\Domain\ValueObject;

use Carbon\CarbonInterface;

interface DataInterface extends \Stringable
{

    public function getDate(): CarbonInterface;

    public function getMinWindSpeed(): WindSpeedInterface;

    public function getMaxWindSpeed(): ?WindSpeedInterface;

    public function getAvgWindSpeed(): ?WindSpeedInterface;

    public function getWindDirection(): ?string;

    public function getTemperature(): ?float;
}
