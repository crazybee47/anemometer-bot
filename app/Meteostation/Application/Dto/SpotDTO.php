<?php

namespace App\Meteostation\Application\Dto;

use App\Meteostation\Domain\Entity\SpotInterface;

class SpotDTO
{
    public string $id;
    public string $name;
    public string $timezone;

    public function __construct(string $id, string $name, string $timezone)
    {
        $this->id = $id;
        $this->name = $name;
        $this->timezone = $timezone;
    }

    public static function fromEntity(SpotInterface $spot): self
    {
        return new self(
            $spot->getId(),
            $spot->getName(),
            $spot->getTimezone(),
        );
    }
}
