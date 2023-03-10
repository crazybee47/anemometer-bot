<?php

namespace App\Shared\Application\Query;

interface QueryBusInterface
{
    /**
     * @param QueryInterface $query
     * @return mixed
     */
    public function execute(QueryInterface $query);
}
