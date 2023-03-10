<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip;

class Eysk extends AbstractOneChipParser
{

    public const SPOT_NAME = 'Ейск';
    public const SPOT_ID = '0240';

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
