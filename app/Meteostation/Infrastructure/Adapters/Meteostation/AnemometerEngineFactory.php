<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation;

use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\EngineInterface;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Exception\UndefinedSpot;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Html\Hurgada;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Html\WindExtreme;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Blaga;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Dolzhanka;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Eysk;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Izhora;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Kronstadt;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Shelkino;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\SpbTakeOff;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\WindguruCz\MuiNeCentral;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\WindguruCz\MuiNeMalibu;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Windy\ElGouna;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Windy\MuiNe;

class AnemometerEngineFactory
{
    /**
     * @var array<string, EngineInterface>
     */
    private array $engines;

    public function __construct()
    {
        $this->engines = [
            WindExtreme::SPOT_ID => app(WindExtreme::class),
            Dolzhanka::SPOT_ID => app(Dolzhanka::class),
            Eysk::SPOT_ID => app(Eysk::class),
            SpbTakeOff::SPOT_ID => app(SpbTakeOff::class),
            Kronstadt::SPOT_ID => app(Kronstadt::class),
            Izhora::SPOT_ID => app(Izhora::class),
            Shelkino::SPOT_ID => app(Shelkino::class),
            Blaga::SPOT_ID => app(Blaga::class),
            MuiNe::SPOT_ID => app(MuiNe::class),
            MuiNeMalibu::SPOT_ID => app(MuiNeMalibu::class),
            MuiNeCentral::SPOT_ID => app(MuiNeCentral::class),
            Hurgada::SPOT_ID => app(Hurgada::class),
            ElGouna::SPOT_ID => app(ElGouna::class),
        ];
    }

    public function getEngine(string $id): EngineInterface
    {
        if (!array_key_exists($id, $this->engines)) {
            throw new UndefinedSpot("Spot with id {$id} is undefined");
        }
        return $this->engines[$id];
    }
}
