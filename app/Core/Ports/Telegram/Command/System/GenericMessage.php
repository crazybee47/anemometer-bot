<?php

namespace App\Core\Ports\Telegram\Command\System;

use App\Core\Domain\Models\User\Settings\TimezoneSetting;
use App\Core\Infrastructure\Service\UserService;
use App\Core\Ports\Telegram\CallbackQuery\Controller\SettingsController;
use App\Core\Ports\Telegram\Command\Settings\Notifications;
use App\Core\Ports\Telegram\Command\Settings\NotificationsParameter;
use App\Core\Ports\Telegram\Command\Settings\ReportFormat;
use App\Core\Ports\Telegram\Command\Settings\SpotSubscription;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\KeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class GenericMessage extends SystemCommand
{

    public const HANDLERS = [
        ReportFormat::HANDLER_NAME => ReportFormat::HANDLER_KEY,
        Notifications::HANDLER_NAME => Notifications::HANDLER_KEY,
        SpotSubscription::HANDLER_NAME => SpotSubscription::HANDLER_KEY,
        NotificationsParameter::HANDLER_NAME => NotificationsParameter::HANDLER_KEY,
        TimezoneSetting::SETTING_NAME => TimezoneSetting::SETTING_KEY,
    ];

    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';


    /**
     * Main command execution
     *
     * @return ServerResponse
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $messageText = $message->getText(true);
        foreach (self::HANDLERS as $handlerName => $handlerKey) {
            if ($messageText === $handlerName) {
                return $this->telegram->executeCommand($handlerKey);
            }
        }

        if ($message->getReplyToMessage() !== null) {
            $repliedMessageUsername = $message->getReplyToMessage()->getFrom()->getUsername();
            $repliedMessageText = $message->getReplyToMessage()->getText(true);

            $isCustomNotificationsSettingsSave = $repliedMessageUsername === env('TELEGRAM_BOT_USERNAME') &&
                (strpos($repliedMessageText, 'Max') !== false || strpos($repliedMessageText, 'Min') !== false || strpos($repliedMessageText, 'Avg') !== false);
            if ($isCustomNotificationsSettingsSave) {
                $user = (new UserService())->initUser($message->getFrom()->getId());

                try {
                    //@todo заменить на команду UpdateCustomNotificationsSettings.php
                    SettingsController::handleCustomNotificationsSettingsSave($user, $messageText);
                } catch (\Throwable $e) {
                    return Request::sendMessage([
                        'chat_id' => $user->getId(),
                        'text' => 'Некорректный формат сообщения',
                        'reply_markup' => self::getKeyboard(),
                    ]);
                }

                return Request::sendMessage([
                    'chat_id' => $user->getId(),
                    'text' => "Ваши настройки сохранены! Мы оповестим вас когда {$parameterName} значение ветра будет \">= {$value} {$preparedDimension}\"",
                    'reply_markup' => GenericMessage::getKeyboard(true),
                ]);
            }
        }

        return $this->replyToChat('Неизвестная команда');
    }

    public static function getKeyboard(bool $hasCustomSettings = false): Keyboard
    {
        $buttons = [
            ReportFormat::HANDLER_NAME,
            Notifications::HANDLER_NAME,
        ];
        $additionalButtons = $hasCustomSettings
            ? [new KeyboardButton(SpotSubscription::HANDLER_NAME), new KeyboardButton(NotificationsParameter::HANDLER_NAME)]
            : new KeyboardButton(SpotSubscription::HANDLER_NAME);

        return (new Keyboard($buttons, $additionalButtons))
            ->setResizeKeyboard(true)
            ->setSelective(false);
    }
}
