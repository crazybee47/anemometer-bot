<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\WindguruCz;

class MuiNeCentral extends AbstractWindguruCzParser
{

    public const SPOT_NAME = 'Муйне (central)';
    public const SPOT_ID = '3638';

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
