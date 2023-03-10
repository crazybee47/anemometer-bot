<?php

namespace App\Shared\Application\Command;

interface CommandBusInterface
{
    /**
     * @param CommandInterface $query
     * @return int|null|void
     */
    public function execute(CommandInterface $query);
}
