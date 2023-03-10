<?php

namespace App\Meteostation\Application\Dto;

use App\Meteostation\Domain\ValueObject\DataInterface;

class MeteostationDataDTO
{
    private SpotDTO $spot;

    public function __construct()
    {
    }

    public static function fromEntity(DataInterface $data): self
    {
        return new self();
    }

    public function setSpot(SpotDTO $spot): void
    {
        $this->spot = $spot;
    }
}
