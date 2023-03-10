<?php

namespace App\Core\Domain\Models\User;

use App\Core\Domain\Models\User\Settings\TimezoneSetting;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Html\Hurgada;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Html\WindExtreme;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Blaga;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Dolzhanka;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Eysk;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Izhora;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Kronstadt;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Shelkino;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\SpbTakeOff;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\WindguruCz\MuiNeCentral;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\WindguruCz\MuiNeMalibu;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Windy\ElGouna;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Windy\MuiNe;

class Settings
{

    public const SETTINGS_PARAM_NAME = 'settings';
    public const REPORT_FORMAT_PARAM_NAME = 'report_format';
    public const NOTIFICATIONS_SETTINGS_PARAM_NAME = 'notifications';
    public const CUSTOM_NOTIFICATIONS_SETTINGS_PARAM_NAME = 'custom_notifications';
    public const SPOTS_SETTINGS_PARAM_NAME = 'spots';
    public const NOTIFICATIONS_PARAMETER_SETTINGS_PARAM_NAME = 'notifications_parameter';
    public const SPOTS_GROUP_SETTINGS_PARAM_NAME = 'spots_group';

    public const FULL_REPORT_FORMAT_NAME = 'Full';
    public const FULL_REPORT_FORMAT_KEY = 'settings#report_format:full';
    public const METERS_REPORT_FORMAT_NAME = 'm/s';
    public const METERS_REPORT_FORMAT_KEY = 'settings#report_format:meters';
    public const KNOTS_REPORT_FORMAT_NAME = 'knots';
    public const KNOTS_REPORT_FORMAT_KEY = 'settings#report_format:knots';

    public const EVERY_MINUTE_NOTIFICATIONS_NAME = 'Ежеминутно';
    public const EVERY_MINUTE_NOTIFICATIONS_KEY = 'settings#notifications:every_minute';
    public const CUSTOM_NOTIFICATIONS_NAME = 'Custom';
    public const CUSTOM_NOTIFICATIONS_KEY = 'settings#notifications:custom';
    public const CUSTOM_NOTIFICATIONS_VALUE = 'custom';

    public const NOTIFICATIONS_PARAMETER_MIN_NAME = 'Min';
    public const NOTIFICATIONS_PARAMETER_MIN_KEY = 'settings#notifications_parameter:Min';
    public const NOTIFICATIONS_PARAMETER_AVG_NAME = 'Avg';
    public const NOTIFICATIONS_PARAMETER_AVG_KEY = 'settings#notifications_parameter:Avg';
    public const NOTIFICATIONS_PARAMETER_MAX_NAME = 'Max';
    public const NOTIFICATIONS_PARAMETER_MAX_KEY = 'settings#notifications_parameter:Max';

    public const REPORT_FORMATS = [
        self::FULL_REPORT_FORMAT_NAME => self::FULL_REPORT_FORMAT_KEY,
        self::METERS_REPORT_FORMAT_NAME => self::METERS_REPORT_FORMAT_KEY,
        self::KNOTS_REPORT_FORMAT_NAME => self::KNOTS_REPORT_FORMAT_KEY,
    ];

    public const NOTIFICATION_TYPES = [
        self::EVERY_MINUTE_NOTIFICATIONS_NAME => self::EVERY_MINUTE_NOTIFICATIONS_KEY,
        self::CUSTOM_NOTIFICATIONS_NAME => self::CUSTOM_NOTIFICATIONS_KEY,
    ];

    public const NOTIFICATION_PARAMETERS = [
        self::NOTIFICATIONS_PARAMETER_MIN_NAME => self::NOTIFICATIONS_PARAMETER_MIN_KEY,
        self::NOTIFICATIONS_PARAMETER_AVG_NAME => self::NOTIFICATIONS_PARAMETER_AVG_KEY,
        self::NOTIFICATIONS_PARAMETER_MAX_NAME => self::NOTIFICATIONS_PARAMETER_MAX_KEY,
    ];

    public const RUSSIA_COUNTRY_ID = 'country_1';
    public const CRIMEA_COUNTRY_ID = 'country_2';
    public const EGYPT_COUNTRY_ID = 'country_3';
    public const VIETNAM_COUNTRY_ID = 'country_4';
    public const BACK_COUNTRY_ID = 'back';

    public const SPOT_GROUPS = [
        '🇷🇺Россия' => self::RUSSIA_COUNTRY_ID,
        '🌍Крым' => self::CRIMEA_COUNTRY_ID,
        '🇪🇬Египет' => self::EGYPT_COUNTRY_ID,
        '🇻🇳Вьетнам' => self::VIETNAM_COUNTRY_ID,
    ];

    public const SPOTS_BY_GROUP = [
        self::RUSSIA_COUNTRY_ID => [
            Dolzhanka::SPOT_NAME => Dolzhanka::SPOT_ID,
            Eysk::SPOT_NAME => Eysk::SPOT_ID,
            SpbTakeOff::SPOT_NAME => SpbTakeOff::SPOT_ID,
            Kronstadt::SPOT_NAME => Kronstadt::SPOT_ID,
            Izhora::SPOT_NAME => Izhora::SPOT_ID,
            Blaga::SPOT_NAME => Blaga::SPOT_ID,
        ],
        self::CRIMEA_COUNTRY_ID => [
            WindExtreme::SPOT_NAME => WindExtreme::SPOT_ID,
            Shelkino::SPOT_NAME => Shelkino::SPOT_ID,
        ],
        self::EGYPT_COUNTRY_ID => [
            Hurgada::SPOT_NAME => Hurgada::SPOT_ID,
            ElGouna::SPOT_NAME => ElGouna::SPOT_ID,
        ],
        self::VIETNAM_COUNTRY_ID => [
//            MuiNe::SPOT_NAME => MuiNe::SPOT_ID,
            MuiNeMalibu::SPOT_NAME => MuiNeMalibu::SPOT_ID,
            MuiNeCentral::SPOT_NAME => MuiNeCentral::SPOT_ID,
        ],
    ];

    public const SPOTS = [
        WindExtreme::SPOT_NAME => WindExtreme::SPOT_ID,
        Dolzhanka::SPOT_NAME => Dolzhanka::SPOT_ID,
        Eysk::SPOT_NAME => Eysk::SPOT_ID,
        SpbTakeOff::SPOT_NAME => SpbTakeOff::SPOT_ID,
        Kronstadt::SPOT_NAME => Kronstadt::SPOT_ID,
        Izhora::SPOT_NAME => Izhora::SPOT_ID,
        Shelkino::SPOT_NAME => Shelkino::SPOT_ID,
        Blaga::SPOT_NAME => Blaga::SPOT_ID,
        MuiNe::SPOT_NAME => MuiNe::SPOT_ID,
        MuiNeMalibu::SPOT_NAME => MuiNeMalibu::SPOT_ID,
        MuiNeCentral::SPOT_NAME => MuiNeCentral::SPOT_ID,
        Hurgada::SPOT_NAME => Hurgada::SPOT_ID,
        ElGouna::SPOT_NAME => ElGouna::SPOT_ID,
    ];

    public const SPOTS_ENGINES = [
        WindExtreme::SPOT_ID => WindExtreme::class,
        Dolzhanka::SPOT_ID => Dolzhanka::class,
        Eysk::SPOT_ID => Eysk::class,
        SpbTakeOff::SPOT_ID => SpbTakeOff::class,
        Kronstadt::SPOT_ID => Kronstadt::class,
        Izhora::SPOT_ID => Izhora::class,
        Shelkino::SPOT_ID => Shelkino::class,
        Blaga::SPOT_ID => Blaga::class,
        MuiNe::SPOT_ID => MuiNe::class,
        MuiNeMalibu::SPOT_ID => MuiNeMalibu::class,
        MuiNeCentral::SPOT_ID => MuiNeCentral::class,
        Hurgada::SPOT_ID => Hurgada::class,
        ElGouna::SPOT_NAME => ElGouna::class,
    ];

    private string $_reportFormat = self::FULL_REPORT_FORMAT_KEY;

    private string $_notificationsSettings = self::EVERY_MINUTE_NOTIFICATIONS_KEY;

    private ?string $_customNotificationsSettings = null;

    private ?array $_spots = null;

    private ?string $_notificationParameter = null;

    private ?string $_timezone = TimezoneSetting::MOSCOW_VALUE;

    private array $settings = [];

    public function __construct(
        ?string $reportFormat = null,
        ?string $notificationsSettings = null,
        ?string $customNotificationsSettings = null,
        ?array  $spots = [WindExtreme::SPOT_ID],
        ?string $notificationParameter = Settings::NOTIFICATIONS_PARAMETER_MAX_KEY,
        ?string $timezone = null
    )
    {
        if ($reportFormat !== null) {
            $this->_reportFormat = $reportFormat;
        }
        if ($notificationsSettings !== null) {
            $this->_notificationsSettings = $notificationsSettings;
        }
        if ($customNotificationsSettings !== null) {
            $this->_customNotificationsSettings = $customNotificationsSettings;
        }
        if ($spots !== null) {
            $this->_spots = $spots;
        }
        if ($notificationParameter !== null) {
            $this->_notificationParameter = $notificationParameter;
        }
        if ($timezone !== null) {
            $this->_timezone = $timezone;
        }
    }

    public static function fromData(?array $data): self
    {
        $reportFormat = null;
        $notificationsSettings = null;
        $customNotificationsSettings = null;
        $spots = [];
        $parameterName = null;
        $timezone = null;
        if ($data !== null) {
            $reportFormat = $data[self::REPORT_FORMAT_PARAM_NAME] ?? null;
            $notificationsSettings = $data[self::NOTIFICATIONS_SETTINGS_PARAM_NAME] ?? null;
            $customNotificationsSettings = $data[self::CUSTOM_NOTIFICATIONS_SETTINGS_PARAM_NAME] ?? null;
            $parameterName = $data[self::NOTIFICATIONS_PARAMETER_SETTINGS_PARAM_NAME] ?? null;
            $spots = $data[self::SPOTS_SETTINGS_PARAM_NAME] ?? null;
            if ($spots !== null) {
                $spots = explode(',', $spots);
            } else {
                $spots = [WindExtreme::SPOT_ID];
            }
            $timezone = $data[TimezoneSetting::SETTING_KEY] ?? null;
        }
        return new self($reportFormat, $notificationsSettings, $customNotificationsSettings, $spots, $parameterName, $timezone);
    }

    /**
     * @return string
     */
    public function getReportFormat(): string
    {
        return $this->_reportFormat;
    }

    /**
     * @return string
     */
    public function getNotificationsSettings(): string
    {
        return $this->_notificationsSettings;
    }

    /**
     * @return CustomNotificationsSettings|null
     */
    public function getCustomNotificationsSettings(): ?CustomNotificationsSettings
    {
        if ($this->_customNotificationsSettings === null) {
            return null;
        }

        [$operator, $value, $dimension] = explode(' ', $this->_customNotificationsSettings);

        return new CustomNotificationsSettings($operator, $dimension, $value);
    }

    /**
     * @return array|null
     */
    public function getSpots(): ?array
    {
        if ($this->_spots === null) {
            return null;
        }
        return array_filter($this->_spots);
    }

    /**
     * @param string $reportFormat
     */
    public function setReportFormat(string $reportFormat): void
    {
        $this->_reportFormat = $reportFormat;
    }

    /**
     * @param string $notificationsSettings
     */
    public function setNotificationsSettings(string $notificationsSettings): void
    {
        $this->_notificationsSettings = $notificationsSettings;
    }

    /**
     * @param string|null $customNotificationsSettings
     */
    public function setCustomNotificationsSettings(?string $customNotificationsSettings): void
    {
        $this->_customNotificationsSettings = $customNotificationsSettings;
    }

    /**
     * @param array|null $spots
     */
    public function setSpots(?array $spots): void
    {
        $this->_spots = $spots;
    }

    /**
     * @return string
     */
    public function getNotificationParameter(): string
    {
        return $this->_notificationParameter ?? Settings::NOTIFICATIONS_PARAMETER_MAX_KEY;
    }

    /**
     * @param string $notificationParameter
     */
    public function setNotificationParameter(string $notificationParameter): void
    {
        $this->_notificationParameter = $notificationParameter;
    }

    /**
     * @return string|null
     */
    public function getTimezone(): ?string
    {
        return $this->_timezone;
    }

    /**
     * @param string|null $timezone
     */
    public function setTimezone(?string $timezone): void
    {
        $this->_timezone = $timezone;
    }

    private function _getSpotsForSave(): ?string
    {
        if ($this->getSpots() === null) {
            return null;
        }
        return implode($this->getSpots(), ',');
    }

    /**
     * @return string|null
     */
    private function _getCustomNotificationsSettings(): ?string
    {
        return $this->_customNotificationsSettings;
    }
}
