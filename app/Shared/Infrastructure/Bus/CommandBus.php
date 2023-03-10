<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Shared\Infrastructure\Bus\Exception\HandlerNotFoundException;

class CommandBus implements CommandBusInterface
{
    public function execute(CommandInterface $command)
    {
        $handler = $this->getHandler($command);
        return dispatch_sync($command, $handler);
    }

    private function getHandler(CommandInterface $command): CommandHandlerInterface
    {
        $handlerClass = get_class($command) . 'Handler';
        if (!class_exists($handlerClass)) {
            throw new HandlerNotFoundException('Not found handler for command: ' . get_class($command));
        }
        return app($handlerClass);
    }
}
