<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\WindguruCz;

class MuiNeMalibu extends AbstractWindguruCzParser
{

    public const SPOT_NAME = 'Муйне (Malibu)';
    public const SPOT_ID = '3661';

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
