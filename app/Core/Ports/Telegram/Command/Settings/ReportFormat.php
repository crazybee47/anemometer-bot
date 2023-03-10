<?php

namespace App\Core\Ports\Telegram\Command\Settings;

use App\Core\Domain\Models\User\Settings;
use App\Core\Infrastructure\Service\UserService;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\ServerResponse;

class ReportFormat extends UserCommand
{

    public const HANDLER_NAME = 'Формат отчета';
    public const HANDLER_KEY = 'report_format';

    /**
     * @var string
     */
    protected $name = self::HANDLER_KEY;

    /**
     * @var string
     */
    protected $description = 'Report format settings command';

    /**
     * @var string
     */
    protected $usage = '/report_format';


    public function execute(): ServerResponse
    {
        $chatId = $this->getMessage()->getChat()->getId();
        $user = (new UserService())->initUser($chatId);

        $reportFormats = [];
        foreach (Settings::REPORT_FORMATS as $reportFormatName => $reportFormatKey) {
            $preparedFormatName = $reportFormatName;
            if ($reportFormatKey === $user->getSettings()->getReportFormat()) {
                $preparedFormatName .= ' ✅';
            }
            $reportFormats[] = new InlineKeyboardButton(['text' => $preparedFormatName, 'callback_data' => $reportFormatKey]);
        }
        $inlineKeyboard = new InlineKeyboard($reportFormats);

        return $this->replyToChat('Настройки формата отчета', [
            'reply_markup' => $inlineKeyboard,
        ]);
    }

}
