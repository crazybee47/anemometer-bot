<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Windy;

class Kazantip extends AbstractWindyParser
{

    public const SPOT_NAME = 'Мысовое (Kazantip Bay East)';
    public const SPOT_ID = '1chipru_5ccf7ff8da24';

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
