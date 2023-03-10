<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation;

use App\Meteostation\Application\Adapters\MeteostationAdapterInterface;
use App\Meteostation\Domain\ValueObject\DataInterface;

class MeteostationAdapter implements MeteostationAdapterInterface
{

    private AnemometerEngineFactory $factory;

    public function __construct(AnemometerEngineFactory $factory)
    {
        $this->factory = $factory;
    }

    public function getSpotData(string $spotId): DataInterface
    {
        $engine = $this->factory->getEngine($spotId);
        return $engine->getData();
    }
}
