<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\ValueObject\User\Settings\NotificationsFrequency;
use App\Core\Domain\ValueObject\User\Settings\ReportFormat;
use App\Core\Domain\ValueObject\User\Settings\WindSpeedCompareParameter;

class SettingsService
{

    /**
     * @return ReportFormat[]
     */
    public function getReportFormats(): array
    {
        return [
            new ReportFormat(ReportFormat::FULL_REPORT_FORMAT_KEY, ReportFormat::FULL_REPORT_FORMAT_NAME),
            new ReportFormat(ReportFormat::METERS_REPORT_FORMAT_KEY, ReportFormat::METERS_REPORT_FORMAT_NAME),
            new ReportFormat(ReportFormat::KNOTS_REPORT_FORMAT_KEY, ReportFormat::KNOTS_REPORT_FORMAT_NAME),
        ];
    }

    /**
     * @return NotificationsFrequency[]
     */
    public function getNotificationFrequencies(): array
    {
        return [
            new NotificationsFrequency(NotificationsFrequency::EVERY_MINUTE_NOTIFICATIONS_KEY, NotificationsFrequency::EVERY_MINUTE_NOTIFICATIONS_NAME),
            new NotificationsFrequency(NotificationsFrequency::CUSTOM_NOTIFICATIONS_KEY, NotificationsFrequency::CUSTOM_NOTIFICATIONS_NAME),
        ];
    }

    /**
     * @return WindSpeedCompareParameter[]
     */
    public function getWindSpeedCompareParameters(): array
    {
        return [
            new WindSpeedCompareParameter(WindSpeedCompareParameter::NOTIFICATIONS_PARAMETER_MIN_KEY, WindSpeedCompareParameter::NOTIFICATIONS_PARAMETER_MIN_NAME),
            new WindSpeedCompareParameter(WindSpeedCompareParameter::NOTIFICATIONS_PARAMETER_AVG_KEY, WindSpeedCompareParameter::NOTIFICATIONS_PARAMETER_AVG_NAME),
            new WindSpeedCompareParameter(WindSpeedCompareParameter::NOTIFICATIONS_PARAMETER_MAX_KEY, WindSpeedCompareParameter::NOTIFICATIONS_PARAMETER_MAX_NAME),
        ];
    }
}
