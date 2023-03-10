<?php

namespace App\Meteostation\Application\Query;

use App\Shared\Application\Query\QueryInterface;

class GetData implements QueryInterface
{
    private string $spotId;

    public function __construct(string $spotId)
    {
        $this->spotId = $spotId;
    }

    /**
     * @return string
     */
    public function getSpotId(): string
    {
        return $this->spotId;
    }
}
