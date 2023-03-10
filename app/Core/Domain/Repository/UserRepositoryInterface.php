<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\User;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function getAll(): array;
}
