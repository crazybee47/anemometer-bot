<?php

namespace App\Meteostation\Domain\ValueObject\WindDimension;

interface WindDimensionInterface
{
    public function getId(): string;

    public function getName(): string;
}
