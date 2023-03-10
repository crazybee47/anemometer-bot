<?php

namespace App\Meteostation\Domain\ValueObject\WindDimension;

class Knots implements WindDimensionInterface
{
    public const ID = 'knots';
    public const NAME = 'knots';

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
