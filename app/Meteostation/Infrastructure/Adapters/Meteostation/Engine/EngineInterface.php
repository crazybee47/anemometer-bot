<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine;

use App\Meteostation\Domain\ValueObject\DataInterface;

interface EngineInterface
{
    public function getData(): DataInterface;
}
