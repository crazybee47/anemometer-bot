<?php

namespace App\Core\Ports\Telegram\Command\Settings;

use App\Core\Domain\Models\User\Settings;
use App\Core\Infrastructure\Service\UserService;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;

class Notifications extends UserCommand
{

    public const HANDLER_NAME = 'Настройки уведомлений';
    public const HANDLER_KEY = 'notifications';

    /**
     * @var string
     */
    protected $name = self::HANDLER_KEY;

    /**
     * @var string
     */
    protected $description = 'Notifications settings command';

    /**
     * @var string
     */
    protected $usage = '/notifications';


    public function execute(): ServerResponse
    {
        $chatId = $this->getMessage()->getChat()->getId();
        $user = (new UserService())->initUser($chatId);

        $keyboardButtons = [];
        foreach (Settings::NOTIFICATION_TYPES as $notificationTypeName => $notificationTypeKey) {
            $preparedTypeName = $notificationTypeName;
            if ($notificationTypeKey === $user->getSettings()->getNotificationsSettings()) {
                $preparedTypeName .= ' ✅';
            }
            $keyboardButtons[] = new InlineKeyboardButton(['text' => $preparedTypeName, 'callback_data' => $notificationTypeKey]);
        }

        return $this->replyToChat('Настройки частоты уведомлений', [
            'reply_markup' => new InlineKeyboard($keyboardButtons),
        ]);
    }

}
