<?php

namespace App\Meteostation\Ports\Message;

use App\Meteostation\Application\Service\Meteostation\MeteostationServiceInterface;

class GetDataAction
{
    private MeteostationServiceInterface $meteostationService;

    public function __construct(
        MeteostationServiceInterface $meteostationService
    )
    {
        $this->meteostationService = $meteostationService;
    }

    public function __invoke(GetDataRequest $request)
    {
        return $this->meteostationService->getSpotData($request->getSpotId());
    }
}
