<?php

namespace App\Core\Infrastructure\Service;

use App\Core\Domain\Models\User;
use App\Core\Domain\Service\NotifierInterface;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class TelegramNotifier implements NotifierInterface
{

    public function __construct()
    {
        new Telegram(
            env('TELEGRAM_BOT_TOKEN'),
            env('TELEGRAM_BOT_USERNAME'),
        );
    }

    /**
     * @param string $userId
     * @param string $message
     * @return void
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function notify(User $user, string $message): void
    {
        Request::sendMessage([
            'chat_id' => $user->getId(),
            'text' => $message,
            'parse_mode' => 'markdown',
        ]);
    }
}
