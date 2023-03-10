<?php

namespace App\Core\Ports\Telegram\CallbackQuery\Controller;

use App\Core\Domain\Models\User\Settings;
use App\Core\Domain\Models\User\Settings\TimezoneSetting;
use App\Core\Infrastructure\Service\UserService;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;

class TimezoneController extends AbstractController
{

    public function saveTimezone(CallbackQuery $callbackQuery): ServerResponse
    {
        $request = self::parseCallbackQuery($callbackQuery);
        if ($request->getType() !== Settings::SETTINGS_PARAM_NAME) {
            return $callbackQuery->answer();
        }

        if ($request->getParameter() !== TimezoneSetting::SETTING_KEY) {
            return $callbackQuery->answer();
        }

        $newTimezone = $request->getValue();
        $user = (new UserService())->initUser($callbackQuery->getFrom()->getId());
        $settings = $user->getSettings();
        $settings->setTimezone($newTimezone);
        $user->setSettings($settings);
        $user->save();

        return $callbackQuery->answer([
            'text' => 'Часовой пояс успешно изменен',
            'show_alert' => false,
            'cache_time' => 1,
        ]);
    }
}
