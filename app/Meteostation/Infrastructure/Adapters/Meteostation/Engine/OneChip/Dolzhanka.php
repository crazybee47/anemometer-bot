<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip;

class Dolzhanka extends AbstractOneChipParser
{

    public const SPOT_NAME = 'Должанская коса';
    public const SPOT_ID = '0243';

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
