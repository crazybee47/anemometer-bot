<?php

namespace App\Meteostation\Application\Query;

use App\Meteostation\Application\Adapters\MeteostationAdapterInterface;
use App\Meteostation\Application\Dto\MeteostationDataDTO;
use App\Shared\Application\Query\QueryHandlerInterface;

class GetDataHandler implements QueryHandlerInterface
{
    private MeteostationAdapterInterface $meteostationAdapter;

    public function __construct(MeteostationAdapterInterface $meteostationAdapter)
    {
        $this->meteostationAdapter = $meteostationAdapter;
    }

    public function __invoke(GetData $query)
    {
        try {
            $data = $this->meteostationAdapter->getSpotData($query->getSpotId());
        } catch (\Throwable $e) {
            //@todo отлов ексепшенов
            dd($e);
        }

        return MeteostationDataDTO::fromEntity($data);
    }
}
