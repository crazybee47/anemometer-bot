<?php

namespace App\Core\Ports\Telegram\CallbackQuery\Controller;

use App\Core\Domain\Models\User;
use App\Core\Domain\Models\User\CustomNotificationsSettings;
use App\Core\Domain\Models\User\Settings;
use App\Core\Infrastructure\Service\UserService;
use App\Core\Ports\Telegram\CallbackQuery\Request\RequestInterface;
use App\Core\Ports\Telegram\CallbackQuery\Request\SettingsRequest;
use App\Core\Ports\Telegram\Command\System\GenericMessage;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class SettingsController extends AbstractController
{

    public function reportFormatSave(CallbackQuery $callbackQuery): ServerResponse
    {
        $request = self::parseCallbackQuery($callbackQuery);
        if ($request->getType() !== Settings::SETTINGS_PARAM_NAME) {
            return $callbackQuery->answer();
        }

        if ($request->getParameter() !== Settings::REPORT_FORMAT_PARAM_NAME) {
            return $callbackQuery->answer();
        }

        $user = (new UserService())->initUser($callbackQuery->getFrom()->getId());
        $settings = $user->getSettings();
        $settings->setReportFormat($callbackQuery->getData());
        $user->setSettings($settings);
        $user->save();

        $reportFormats = [];
        foreach (Settings::REPORT_FORMATS as $reportFormatName => $reportFormatKey) {
            $preparedFormatName = $reportFormatName;
            if ($reportFormatKey === $user->getSettings()->getReportFormat()) {
                $preparedFormatName .= ' ✅';
            }
            $reportFormats[] = new InlineKeyboardButton(['text' => $preparedFormatName, 'callback_data' => $reportFormatKey]);
        }
        $inlineKeyboard = new InlineKeyboard($reportFormats);

        Request::editMessageText([
            'text' => 'Настройки формата отчета',
            'chat_id' => $callbackQuery->getFrom()->getId(),
            'message_id' => $callbackQuery->getMessage()->getMessageId(),
            'reply_markup' => $inlineKeyboard,
        ]);
        return $callbackQuery->answer([
            'text' => 'Настройки сохранены: ' . $request->getValue(),
            'show_alert' => false,
            'cache_time' => 1,
        ]);
    }

    public function notificationsSettingsSave(CallbackQuery $callbackQuery): ServerResponse
    {
        $request = self::parseCallbackQuery($callbackQuery);
        if ($request->getType() !== Settings::SETTINGS_PARAM_NAME) {
            return $callbackQuery->answer();
        }

        if ($request->getParameter() !== Settings::NOTIFICATIONS_SETTINGS_PARAM_NAME) {
            return $callbackQuery->answer();
        }

        $user = (new UserService())->initUser($callbackQuery->getFrom()->getId());
        $settings = $user->getSettings();
        $settings->setNotificationsSettings($callbackQuery->getData());
        $user->setSettings($settings);
        $user->save();

        $keyboardButtons = [];
        foreach (Settings::NOTIFICATION_TYPES as $notificationTypeName => $notificationTypeKey) {
            $preparedTypeName = $notificationTypeName;
            if ($notificationTypeKey === $user->getSettings()->getNotificationsSettings()) {
                $preparedTypeName .= ' ✅';
            }
            $keyboardButtons[] = new InlineKeyboardButton(['text' => $preparedTypeName, 'callback_data' => $notificationTypeKey]);
        }
        $inlineKeyboard = new InlineKeyboard($keyboardButtons);
        Request::editMessageText([
            'text' => 'Настройки частоты уведомлений',
            'chat_id' => $callbackQuery->getFrom()->getId(),
            'message_id' => $callbackQuery->getMessage()->getMessageId(),
            'reply_markup' => $inlineKeyboard,
        ]);

        if ($request->getValue() === Settings::CUSTOM_NOTIFICATIONS_VALUE) {
            $reportFormat = $user->getSettings()->getReportFormat();
            $dimension = null;
            if ($reportFormat === Settings::KNOTS_REPORT_FORMAT_KEY) {
                $dimension = CustomNotificationsSettings::KNOTS_DIMENSION;
            }
            if ($reportFormat === Settings::METERS_REPORT_FORMAT_KEY) {
                $dimension = CustomNotificationsSettings::METERS_DIMENSION;
            }
            $parameterName = array_search($user->getSettings()->getNotificationParameter(), Settings::NOTIFICATION_PARAMETERS);
            $message = "Напишите {$parameterName} значение ветра по примеру: `12 knots` или `6 m/s`";
            if ($dimension !== null) {
                $preparedDimension = CustomNotificationsSettings::getHumanDimension($dimension);
                $message = "Напишите {$parameterName} значение ветра в {$preparedDimension}";
            }
            return Request::sendMessage([
                'chat_id' => $user->getId(),
                'text' => $message,
                'parse_mode' => 'markdown',
                'reply_markup' => Keyboard::forceReply(),
            ]);
        }

        return Request::sendMessage([
            'chat_id' => $user->getId(),
            'text' => 'Настройки уведомлений сохранены!',
            'parse_mode' => 'markdown',
            'reply_markup' => GenericMessage::getKeyboard(),
        ]);
    }

    public function notificationsParameterSave(CallbackQuery $callbackQuery): ServerResponse
    {
        $request = self::parseCallbackQuery($callbackQuery);
        if ($request->getType() !== Settings::SETTINGS_PARAM_NAME) {
            return $callbackQuery->answer();
        }
        if ($request->getParameter() !== Settings::NOTIFICATIONS_PARAMETER_SETTINGS_PARAM_NAME) {
            return $callbackQuery->answer();
        }

        $user = (new UserService())->initUser($callbackQuery->getFrom()->getId());
        $settings = $user->getSettings();
        $settings->setNotificationParameter($callbackQuery->getData());
        $user->setSettings($settings);
        $user->save();

        $parametersButtons = [];
        foreach (Settings::NOTIFICATION_PARAMETERS as $parameterName => $parameterValue) {
            $preparedParameterName = $parameterName;
            if ($parameterValue === $user->getSettings()->getNotificationParameter()) {
                $preparedParameterName .= ' ✅';
            }
            $parametersButtons[] = new InlineKeyboardButton(['text' => $preparedParameterName, 'callback_data' => $parameterValue]);
        }
        Request::editMessageText([
            'text' => 'Настройки параметра уведомлений',
            'chat_id' => $callbackQuery->getFrom()->getId(),
            'message_id' => $callbackQuery->getMessage()->getMessageId(),
            'reply_markup' => new InlineKeyboard($parametersButtons),
        ]);

        return $callbackQuery->answer();
    }

    public static function handleCustomNotificationsSettingsSave(User $user, string $settings): ServerResponse
    {
        $settingsData = explode(' ', $settings);
        $userSettings = $user->getSettings();

        if (count($settingsData) === 2) {
            [$value, $dimension] = $settingsData;
            if (!is_numeric($value) || !in_array($dimension, CustomNotificationsSettings::AVAILABLE_DIMENSIONS)) {
                return self::handleIncorrectCustomNotificationsSettingsError($user);
            }
            if ($dimension === CustomNotificationsSettings::METERS_ADDITIONAL_DIMENSION) {
                $dimension = CustomNotificationsSettings::METERS_DIMENSION;
            }
            $settingsToSave = CustomNotificationsSettings::DEFAULT_OPERATOR . ' ' . $value . ' ' . $dimension;
        } else {
            $value = str_replace(',', '.', $settings);
            $reportFormat = $userSettings->getReportFormat();
            if (!is_numeric($value) || $reportFormat === Settings::FULL_REPORT_FORMAT_KEY) {
                return self::handleIncorrectCustomNotificationsSettingsError($user);
            }
            $dimension = null;
            if ($reportFormat === Settings::KNOTS_REPORT_FORMAT_KEY) {
                $dimension = CustomNotificationsSettings::KNOTS_DIMENSION;
            }
            if ($reportFormat === Settings::METERS_REPORT_FORMAT_KEY) {
                $dimension = CustomNotificationsSettings::METERS_DIMENSION;
            }
            $settingsToSave = CustomNotificationsSettings::DEFAULT_OPERATOR . ' ' . $value . ' ' . $dimension;
        }
        self::_saveCustomNotificationsSettings($user, Settings::CUSTOM_NOTIFICATIONS_KEY, $settingsToSave);
        $preparedDimension = CustomNotificationsSettings::getHumanDimension($dimension);
        $parameterName = array_search($userSettings->getNotificationParameter(), Settings::NOTIFICATION_PARAMETERS);

        return Request::sendMessage([
            'chat_id' => $user->getId(),
            'text' => "Ваши настройки сохранены! Мы оповестим вас когда {$parameterName} значение ветра будет \">= {$value} {$preparedDimension}\"",
            'reply_markup' => GenericMessage::getKeyboard(true),
        ]);
    }

    public static function handleIncorrectCustomNotificationsSettingsError(User $user): ServerResponse
    {
        self::_saveCustomNotificationsSettings($user, Settings::EVERY_MINUTE_NOTIFICATIONS_KEY, null);

        return Request::sendMessage([
            'chat_id' => $user->getId(),
            'text' => 'Некорректный формат сообщения',
            'reply_markup' => GenericMessage::getKeyboard(),
        ]);

    }

    /**
     * @param CallbackQuery $callbackQuery
     * @return SettingsRequest
     */
    protected static function parseCallbackQuery(CallbackQuery $callbackQuery): RequestInterface
    {
        $callbackData = $callbackQuery->getData();
        [$callbackType, $callbackData] = explode('#', $callbackData);
        [$param, $value] = explode(':', $callbackData);
        return new SettingsRequest($callbackType, $callbackData, $param, $value);
    }

    private static function _saveCustomNotificationsSettings(User $user, string $notificationsType, ?string $customSettingsValue): void
    {
        $userSettings = $user->getSettings();
        $userSettings->setNotificationsSettings($notificationsType);
        $userSettings->setCustomNotificationsSettings($customSettingsValue);
        $user->setSettings($userSettings);
        $user->save();
    }
}
