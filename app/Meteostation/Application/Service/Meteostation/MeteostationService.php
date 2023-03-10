<?php

namespace App\Meteostation\Application\Service\Meteostation;

use App\Meteostation\Application\Dto\MeteostationDataDTO;
use App\Meteostation\Application\Query\FindSpot;
use App\Meteostation\Application\Query\GetData;
use App\Shared\Application\Query\QueryBusInterface;

class MeteostationService implements MeteostationServiceInterface
{
    private QueryBusInterface $queryBus;

    public function __construct(QueryBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function getSpotData(string $spotId): MeteostationDataDTO
    {
        $spotDTO = $this->queryBus->execute(new FindSpot($spotId));
        /** @var MeteostationDataDTO $dataDTO */
        $dataDTO = $this->queryBus->execute(new GetData($spotId));
        $dataDTO->setSpot($spotDTO);

        return $dataDTO;
    }

}
