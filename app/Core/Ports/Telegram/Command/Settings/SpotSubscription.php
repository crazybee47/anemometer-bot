<?php

namespace App\Core\Ports\Telegram\Command\Settings;

use App\Core\Domain\Models\User\Settings;
use App\Core\Infrastructure\Service\UserService;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;

class SpotSubscription extends UserCommand
{

    public const HANDLER_NAME = 'Подписка на споты';
    public const HANDLER_KEY = 'spots';

    /**
     * @var string
     */
    protected $name = self::HANDLER_KEY;

    /**
     * @var string
     */
    protected $description = 'Spots subscription settings command';

    /**
     * @var string
     */
    protected $usage = '/spots';


    public function execute(): ServerResponse
    {
        $chatId = $this->getMessage()->getChat()->getId();
        $user = (new UserService())->initUser($chatId);

        return $this->replyToChat('Подписка на споты', [
            'reply_markup' => self::getSpotGroupsKeyboard($user->getSettings()->getSpots()),
        ]);
    }

    public static function getSpotGroupsKeyboard(array $userSpots = [])
    {
        $keyboardButtons = [];
        foreach (array_chunk(array_keys(Settings::SPOT_GROUPS), 2) as $spotGroupsChunk) {
            $keyboardRow = [];
            foreach ($spotGroupsChunk as $groupName) {
                $groupId = Settings::SPOT_GROUPS[$groupName];
                $groupSpots = Settings::SPOTS_BY_GROUP[$groupId];
                $isUserSubscribeToSpotsFromGroup = count(array_intersect($userSpots, $groupSpots)) > 0;
                $preparedGroupName = $groupName;
                if ($isUserSubscribeToSpotsFromGroup) {
                    $preparedGroupName = "✅ {$preparedGroupName}";
                }
                $preparedCallbackData = Settings::SETTINGS_PARAM_NAME . '#' . Settings::SPOTS_GROUP_SETTINGS_PARAM_NAME . ':' . $groupId;
                $keyboardRow[] = new InlineKeyboardButton(['text' => $preparedGroupName, 'callback_data' => $preparedCallbackData]);
            }
            $keyboardButtons[] = $keyboardRow;
        }

        return new InlineKeyboard(...$keyboardButtons);
    }

}
