<?php

namespace App\Core\Ports\Telegram\Command\System;

use App\Core\Infrastructure\Service\UserService;
use Longman\TelegramBot\Commands\UserCommands\StartCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class Start extends StartCommand
{

    public function execute(): ServerResponse
    {
        $chatId = $this->getMessage()->getChat()->getId();
        $user = (new UserService())->initUser($chatId);

        return Request::sendMessage([
            'chat_id' => $chatId,
            'text' => "Вы успешно подписались на Wind Factory ⭕️NLINE! Теперь мы будем присылать прогноз каждую минуту =)))",
            'reply_markup' => GenericMessage::getKeyboard(),
        ]);
    }

}
