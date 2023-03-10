<?php

namespace App\Meteostation\Application\Query;

use App\Meteostation\Application\Dto\SpotDTO;
use App\Meteostation\Domain\Repository\SpotRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Exception\DomainResourceNotFoundException;

class FindSpotHandler implements QueryHandlerInterface
{
    private SpotRepositoryInterface $repository;

    public function __construct(SpotRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindSpot $query): SpotDTO
    {
        $spot = $this->repository->find($query->getSpotId());
        if ($spot === null) {
            throw new DomainResourceNotFoundException("Spot with id {$query->getSpotId()} isn't found.");
        }
        return SpotDTO::fromEntity($spot);
    }
}
