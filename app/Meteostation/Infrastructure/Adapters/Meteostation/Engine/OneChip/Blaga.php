<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip;

class Blaga extends AbstractOneChipParser
{

    public const SPOT_NAME = 'Блага (Бугазская коса)';
    public const SPOT_ID = '0190';

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
