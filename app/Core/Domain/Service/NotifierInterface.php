<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Models\User;

interface NotifierInterface
{
    public function notify(User $user, string $message): void;
}
