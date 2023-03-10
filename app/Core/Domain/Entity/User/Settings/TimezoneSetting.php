<?php

namespace App\Core\Domain\Models\User\Settings;

class TimezoneSetting implements SettingInterface
{
    public const SETTING_NAME = 'Часовой пояс';
    public const SETTING_KEY = 'timezone';

    public const MOSCOW_NAME = '🇷🇺Москва';
    public const MOSCOW_VALUE = 'settings#timezone:Europe/Moscow';
    public const EGYPT_NAME = '🇪🇬Египет';
    public const EGYPT_VALUE = 'settings#timezone:Africa/Cairo';
    public const VIETNAM_NAME = '🇻🇳Вьетнам';
    public const VIETNAM_VALUE = 'settings#timezone:Asia/Ho_Chi_Minh';

    private string $value;

    public function __construct(string $value = self::MOSCOW_VALUE)
    {
        $this->value = $value;
    }

    public function getName(): string
    {
        return self::SETTING_NAME;
    }

    public function getKey(): string
    {
        return self::SETTING_KEY;
    }

    public function getSelectedValue(): string
    {
        return $this->value;
    }

    public function getAvailableValues(): array
    {
        return [
            new SettingValue(self::MOSCOW_NAME, self::MOSCOW_VALUE),
            new SettingValue(self::EGYPT_NAME, self::EGYPT_VALUE),
            new SettingValue(self::VIETNAM_NAME, self::VIETNAM_VALUE),
        ];
    }

    public function jsonSerialize()
    {
        return [
            $this->getKey() => $this->getSelectedValue(),
        ];
    }
}
