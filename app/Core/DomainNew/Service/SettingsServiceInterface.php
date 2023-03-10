<?php

namespace App\Core\DomainNew\Service;

use App\Core\Domain\ValueObject\User\Settings\NotificationsFrequency;
use App\Core\Domain\ValueObject\User\Settings\ReportFormat;
use App\Core\Domain\ValueObject\User\Settings\WindSpeedCompareParameter;

interface SettingsServiceInterface
{
    /**
     * @return ReportFormat[]
     */
    public function getReportFormats(): array;

    /**
     * @return NotificationsFrequency[]
     */
    public function getNotificationFrequencies(): array;

    /**
     * @return WindSpeedCompareParameter[]
     */
    public function getWindSpeedCompareParameters(): array;
}
