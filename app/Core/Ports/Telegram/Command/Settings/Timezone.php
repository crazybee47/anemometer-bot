<?php

namespace App\Core\Ports\Telegram\Command\Settings;

use App\Core\Domain\Models\User\Settings\SettingInterface;
use App\Core\Domain\Models\User\Settings\TimezoneSetting;
use App\Core\Infrastructure\Service\UserService;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;

class Timezone extends UserCommand
{

    protected $name = TimezoneSetting::SETTING_KEY;

    protected $usage = '/' . TimezoneSetting::SETTING_KEY;

    /**
     * @var string
     */
    protected $description = 'Timezone settings command';

    private SettingInterface $setting;

    public function execute(): ServerResponse
    {
        $chatId = $this->getMessage()->getChat()->getId();
        $user = (new UserService())->initUser($chatId);
        $this->setting = new TimezoneSetting($user->getSettings()->getTimezone());

        $parametersButtons = [];
        foreach ($this->setting->getAvailableValues() as $availableValue) {
            $preparedParameterName = $availableValue->getName();
            if ($availableValue->getValue() === $user->getSettings()->getTimezone()) {
                $preparedParameterName .= ' ✅';
            }
            $parametersButtons[] = new InlineKeyboardButton(['text' => $preparedParameterName, 'callback_data' => $availableValue->getValue()]);
        }

        return $this->replyToChat('Настройки часового пояса', [
            'reply_markup' => new InlineKeyboard($parametersButtons),
        ]);
    }

}
