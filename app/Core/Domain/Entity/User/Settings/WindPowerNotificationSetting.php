<?php

namespace App\Core\Domain\Models\User\Settings;

class WindPowerNotificationSetting implements SettingInterface
{
    public const NOTIFICATIONS_PARAMETER_MIN_NAME = 'Min';
    public const NOTIFICATIONS_PARAMETER_MIN_KEY = 'settings#notifications_parameter:Min';
    public const NOTIFICATIONS_PARAMETER_AVG_NAME = 'Avg';
    public const NOTIFICATIONS_PARAMETER_AVG_KEY = 'settings#notifications_parameter:Avg';
    public const NOTIFICATIONS_PARAMETER_MAX_NAME = 'Max';
    public const NOTIFICATIONS_PARAMETER_MAX_KEY = 'settings#notifications_parameter:Max';

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getKey(): string
    {
        return 'notifications_parameter';
    }

    public function getSelectedValue(): string
    {
        return $this->value;
    }

    public function getAvailableValues(): array
    {
        return [
            new SettingValue(self::NOTIFICATIONS_PARAMETER_MIN_NAME, self::NOTIFICATIONS_PARAMETER_MIN_KEY),
            new SettingValue(self::NOTIFICATIONS_PARAMETER_AVG_NAME, self::NOTIFICATIONS_PARAMETER_AVG_KEY),
            new SettingValue(self::NOTIFICATIONS_PARAMETER_MAX_NAME, self::NOTIFICATIONS_PARAMETER_MAX_KEY),
        ];
    }

    public function jsonSerialize()
    {
        return [
            $this->getKey() => $this->getSelectedValue(),
        ];
    }
}
