<?php

namespace App\Core\Domain\ValueObject\User\Settings;

class ReportFormat extends AbstractSetting
{
    public const FULL_REPORT_FORMAT_NAME = 'Full';
    public const FULL_REPORT_FORMAT_KEY = 'settings#report_format:full';
    public const METERS_REPORT_FORMAT_NAME = 'm/s';
    public const METERS_REPORT_FORMAT_KEY = 'settings#report_format:meters';
    public const KNOTS_REPORT_FORMAT_NAME = 'knots';
    public const KNOTS_REPORT_FORMAT_KEY = 'settings#report_format:knots';
}
