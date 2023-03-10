<?php

namespace App\Core\Ports\Telegram\CallbackQuery\Controller;

use App\Core\Ports\Telegram\CallbackQuery\Request\RequestInterface;
use App\Core\Ports\Telegram\CallbackQuery\Request\SettingsRequest;
use Longman\TelegramBot\Entities\CallbackQuery;

abstract class AbstractController
{

    protected static function parseCallbackQuery(CallbackQuery $callbackQuery): RequestInterface
    {
        $callbackData = $callbackQuery->getData();
        [$callbackType, $callbackData] = explode('#', $callbackData);
        [$param, $value] = explode(':', $callbackData);
        return new SettingsRequest($callbackType, $callbackData, $param, $value);
    }
}
