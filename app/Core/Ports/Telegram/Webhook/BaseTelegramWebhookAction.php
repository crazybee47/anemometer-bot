<?php

namespace App\Core\Ports\Telegram\Webhook;

use Illuminate\Http\Request;

class BaseTelegramWebhookAction
{
    public function __invoke(Request $request)
    {
        try {
            $telegram = new \Longman\TelegramBot\Telegram(
                env('TELEGRAM_BOT_TOKEN'),
                env('TELEGRAM_BOT_USERNAME'),
            );

            $telegram->handle();
        } catch (\Longman\TelegramBot\Exception\TelegramException $e) {
            \Log::error('Error in webhook handling', ['error' => $e]);
        }
    }
}
