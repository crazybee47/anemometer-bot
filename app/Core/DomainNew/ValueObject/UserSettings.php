<?php

namespace App\Core\DomainNew\ValueObject;

use App\Core\Domain\ValueObject\User\Settings\CustomFrequencyRule;
use App\Core\Domain\ValueObject\User\Settings\NotificationsFrequency;
use App\Core\Domain\ValueObject\User\Settings\ReportFormat;
use App\Core\Domain\ValueObject\User\Settings\WindSpeedCompareParameter;
use App\Meteostation\Domain\Entity\Spot;

class UserSettings
{

    /** @var Spot[] $subscribedSpots */
    private array $subscribedSpots;

    private ReportFormat $reportFormat;

    private NotificationsFrequency $notificationsFrequency;

    private ?CustomFrequencyRule $customFrequencyRule;

    private WindSpeedCompareParameter $windSpeedCompareParameter;

    /**
     * @param Spot[] $spots
     * @param ReportFormat $reportFormat
     * @param NotificationsFrequency $notificationsFrequency
     * @param CustomFrequencyRule|null $customFrequencyRule
     * @param WindSpeedCompareParameter $windSpeedCompareParameter
     */
    public function __construct(array $subscribedSpots, ReportFormat $reportFormat, NotificationsFrequency $notificationsFrequency, ?CustomFrequencyRule $customFrequencyRule, WindSpeedCompareParameter $windSpeedCompareParameter)
    {
        $this->subscribedSpots = $subscribedSpots;
        $this->reportFormat = $reportFormat;
        $this->notificationsFrequency = $notificationsFrequency;
        $this->customFrequencyRule = $customFrequencyRule;
        $this->windSpeedCompareParameter = $windSpeedCompareParameter;
    }

    /**
     * @return Spot[]
     */
    public function getSubscribedSpots(): array
    {
        return $this->subscribedSpots;
    }

    /**
     * @param Spot[] $subscribedSpots
     */
    public function setSubscribedSpots(array $subscribedSpots): void
    {
        $this->subscribedSpots = $subscribedSpots;
    }

    /**
     * @return ReportFormat
     */
    public function getReportFormat(): ReportFormat
    {
        return $this->reportFormat;
    }

    /**
     * @param ReportFormat $reportFormat
     */
    public function setReportFormat(ReportFormat $reportFormat): void
    {
        $this->reportFormat = $reportFormat;
    }

    /**
     * @return NotificationsFrequency
     */
    public function getNotificationsFrequency(): NotificationsFrequency
    {
        return $this->notificationsFrequency;
    }

    /**
     * @param NotificationsFrequency $notificationsFrequency
     */
    public function setNotificationsFrequency(NotificationsFrequency $notificationsFrequency): void
    {
        $this->notificationsFrequency = $notificationsFrequency;
    }

    /**
     * @return CustomFrequencyRule|null
     */
    public function getCustomFrequencyRule(): ?CustomFrequencyRule
    {
        return $this->customFrequencyRule;
    }

    /**
     * @param CustomFrequencyRule|null $customFrequencyRule
     */
    public function setCustomFrequencyRule(?CustomFrequencyRule $customFrequencyRule): void
    {
        $this->customFrequencyRule = $customFrequencyRule;
    }

    /**
     * @return WindSpeedCompareParameter
     */
    public function getWindSpeedCompareParameter(): WindSpeedCompareParameter
    {
        return $this->windSpeedCompareParameter;
    }

    /**
     * @param WindSpeedCompareParameter $windSpeedCompareParameter
     */
    public function setWindSpeedCompareParameter(WindSpeedCompareParameter $windSpeedCompareParameter): void
    {
        $this->windSpeedCompareParameter = $windSpeedCompareParameter;
    }
}
