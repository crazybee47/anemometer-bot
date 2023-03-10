<?php

namespace App\Core\Domain\ValueObject\User\Settings;

class WindSpeedCompareParameter extends AbstractSetting
{
    public const NOTIFICATIONS_PARAMETER_MIN_NAME = 'Min';
    public const NOTIFICATIONS_PARAMETER_MIN_KEY = 'settings#notifications_parameter:Min';
    public const NOTIFICATIONS_PARAMETER_AVG_NAME = 'Avg';
    public const NOTIFICATIONS_PARAMETER_AVG_KEY = 'settings#notifications_parameter:Avg';
    public const NOTIFICATIONS_PARAMETER_MAX_NAME = 'Max';
    public const NOTIFICATIONS_PARAMETER_MAX_KEY = 'settings#notifications_parameter:Max';
}
