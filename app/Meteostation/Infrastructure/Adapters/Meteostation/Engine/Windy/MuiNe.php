<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Windy;

class MuiNe extends AbstractWindyParser
{

    public const SPOT_NAME = 'Муйне';
    public const SPOT_ID = 'windguru_862462030029697';

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
