<?php

namespace App\Core\Ports\Telegram\CallbackQuery\Controller;

use App\Core\Domain\Models\User\Settings;
use App\Core\Infrastructure\Service\UserService;
use App\Core\Ports\Telegram\CallbackQuery\Request\RequestInterface;
use App\Core\Ports\Telegram\CallbackQuery\Request\SettingsRequest;
use App\Core\Ports\Telegram\Command\Settings\SpotSubscription;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class SpotSubscriptionSettingsController extends AbstractController
{

    public function spotSubscriptionSave(CallbackQuery $callbackQuery): ServerResponse
    {
        $request = self::parseCallbackQuery($callbackQuery);
        if ($request->getType() !== Settings::SETTINGS_PARAM_NAME) {
            return $callbackQuery->answer();
        }

        if ($request->getParameter() !== Settings::SPOTS_SETTINGS_PARAM_NAME) {
            return $callbackQuery->answer();
        }

        $newSpotId = $request->getValue();
        $user = (new UserService())->initUser($callbackQuery->getFrom()->getId());
        $settings = $user->getSettings();
        $spots = $settings->getSpots();
        $isUnsubscribe = false;
        if (in_array($newSpotId, $spots)) {
            $isUnsubscribe = true;
            $spots = array_filter($spots, fn($spotId) => $spotId !== $newSpotId);
        } else {
            $spots[] = $newSpotId;
        }
        $settings->setSpots($spots);
        $user->setSettings($settings);
        $user->save();

        Request::editMessageText([
            'text' => 'Подписка на споты',
            'chat_id' => $callbackQuery->getFrom()->getId(),
            'message_id' => $callbackQuery->getMessage()->getMessageId(),
            'reply_markup' => SpotSubscription::getSpotGroupsKeyboard($spots),
        ]);
        return $callbackQuery->answer([
            'text' => $isUnsubscribe ? 'Вы успешно отписаны от спота' : 'Вы успешно подписаны на спот',
            'show_alert' => false,
            'cache_time' => 1,
        ]);
    }

    public function getGroupSpots(CallbackQuery $callbackQuery): ServerResponse
    {
        $request = self::parseCallbackQuery($callbackQuery);
        if ($request->getType() !== Settings::SETTINGS_PARAM_NAME) {
            return $callbackQuery->answer();
        }

        if ($request->getParameter() !== Settings::SPOTS_GROUP_SETTINGS_PARAM_NAME) {
            return $callbackQuery->answer();
        }

        $chatId = $callbackQuery->getMessage()->getChat()->getId();
        $user = (new UserService())->initUser($chatId);
        $groupId = $request->getValue();
        if ($groupId === Settings::BACK_COUNTRY_ID) {
            return Request::editMessageText([
                'text' => 'Подписка на споты',
                'chat_id' => $callbackQuery->getFrom()->getId(),
                'message_id' => $callbackQuery->getMessage()->getMessageId(),
                'reply_markup' => SpotSubscription::getSpotGroupsKeyboard($user->getSettings()->getSpots()),
            ]);
        }
        if (!array_key_exists($groupId, Settings::SPOTS_BY_GROUP)) {
            return $callbackQuery->answer();
        }

        $keyboardButtons = [];
        $groupSpots = Settings::SPOTS_BY_GROUP[$groupId];
        foreach (array_chunk(array_keys($groupSpots), 2) as $spotsChunk) {
            $keyboardRow = [];
            foreach ($spotsChunk as $spotName) {
                $spotId = $groupSpots[$spotName];
                $preparedSpotName = $spotName;
                if (in_array($spotId, $user->getSettings()->getSpots())) {
                    $preparedSpotName = "✅ {$preparedSpotName}";
                }
                $preparedCallbackData = Settings::SETTINGS_PARAM_NAME . '#' . Settings::SPOTS_SETTINGS_PARAM_NAME . ':' . $spotId;
                $keyboardRow[] = new InlineKeyboardButton(['text' => $preparedSpotName, 'callback_data' => $preparedCallbackData]);
            }
            $keyboardButtons[] = $keyboardRow;
        }
        $keyboardButtons[] = [
            new InlineKeyboardButton(['text' => '< Назад', 'callback_data' => Settings::SETTINGS_PARAM_NAME . '#' . Settings::SPOTS_GROUP_SETTINGS_PARAM_NAME . ':' . Settings::BACK_COUNTRY_ID]),
        ];
        $keyboard = new InlineKeyboard(...$keyboardButtons);

        Request::editMessageText([
            'text' => 'Подписка на споты',
            'chat_id' => $callbackQuery->getFrom()->getId(),
            'message_id' => $callbackQuery->getMessage()->getMessageId(),
            'reply_markup' => $keyboard,
        ]);
        return $callbackQuery->answer();
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
}
