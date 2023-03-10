<?php

namespace App\Core\Ports\Telegram\Command\Settings;

use App\Core\Domain\Models\User\Settings;
use App\Core\Infrastructure\Service\UserService;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;

class NotificationsParameter extends UserCommand
{

    public const HANDLER_NAME = 'min/avg/max';
    public const HANDLER_KEY = 'notifications_parameter';

    /**
     * @var string
     */
    protected $name = self::HANDLER_KEY;

    /**
     * @var string
     */
    protected $description = 'Notifications parameter settings command';

    /**
     * @var string
     */
    protected $usage = '/notifications_parameter';


    public function execute(): ServerResponse
    {
        $chatId = $this->getMessage()->getChat()->getId();
        $user = (new UserService())->initUser($chatId);

        $parametersButtons = [];
        foreach (Settings::NOTIFICATION_PARAMETERS as $parameterName => $parameterValue) {
            $preparedParameterName = $parameterName;
            if ($parameterValue === $user->getSettings()->getNotificationParameter()) {
                $preparedParameterName .= ' ✅';
            }
            $parametersButtons[] = new InlineKeyboardButton(['text' => $preparedParameterName, 'callback_data' => $parameterValue]);
        }

        return $this->replyToChat('Настройки параметра уведомлений', [
            'reply_markup' => new InlineKeyboard($parametersButtons),
        ]);
    }

}
