<?php

namespace App\Meteostation\Domain\ValueObject\WindDimension;

class Meters implements WindDimensionInterface
{
    public const ID = 'ms';
    public const NAME = 'm/s';

    protected string $id = self::ID;

    protected string $name = self::NAME;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
