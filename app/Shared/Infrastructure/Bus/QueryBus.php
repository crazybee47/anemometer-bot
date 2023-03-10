<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Application\Query\QueryInterface;
use App\Shared\Infrastructure\Bus\Exception\HandlerNotFoundException;

class QueryBus implements QueryBusInterface
{

    public function execute(QueryInterface $query)
    {
        $handler = $this->getHandler($query);
        return dispatch_sync($query, $handler);
    }

    private function getHandler(QueryInterface $query): QueryHandlerInterface
    {
        $handlerClass = get_class($query) . 'Handler';
        if (!class_exists($handlerClass)) {
            throw new HandlerNotFoundException('Not found handler for query: ' . get_class($query));
        }
        return app($handlerClass);
    }
}
