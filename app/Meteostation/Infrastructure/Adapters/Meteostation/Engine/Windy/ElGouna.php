<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Windy;

class ElGouna extends AbstractWindyParser
{

    public const SPOT_NAME = 'El Gouna';
    public const SPOT_ID = 'davis_001D0AE088BF';

    public function __construct()
    {
        parent::__construct(self::SPOT_ID);
    }

    protected function getSpotId(): string
    {
        return self::SPOT_ID;
    }

    protected function getSpotName(): string
    {
        return self::SPOT_NAME;
    }
}
