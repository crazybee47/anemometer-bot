<?php

namespace App\Core\Domain\Models\User\Settings;

interface SettingValueInterface extends \JsonSerializable
{
    public function getName(): string;

    public function getValue(): string;
}
