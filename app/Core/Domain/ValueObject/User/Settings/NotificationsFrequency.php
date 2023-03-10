<?php

namespace App\Core\Domain\ValueObject\User\Settings;

class NotificationsFrequency extends AbstractSetting
{
    public const EVERY_MINUTE_NOTIFICATIONS_NAME = 'Ежеминутно';
    public const EVERY_MINUTE_NOTIFICATIONS_KEY = 'settings#notifications:every_minute';
    public const CUSTOM_NOTIFICATIONS_NAME = 'Custom';
    public const CUSTOM_NOTIFICATIONS_KEY = 'settings#notifications:custom';
    public const CUSTOM_NOTIFICATIONS_VALUE = 'custom';
}
