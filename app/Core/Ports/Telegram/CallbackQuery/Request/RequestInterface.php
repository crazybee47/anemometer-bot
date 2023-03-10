<?php

namespace App\Core\Ports\Telegram\CallbackQuery\Request;

interface RequestInterface
{

    public function getType(): string;

    public function getData();

}
