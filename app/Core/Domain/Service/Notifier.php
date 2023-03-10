<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Models\User;
use App\Core\Domain\Models\User\CustomNotificationsSettings;
use App\Core\Domain\Models\User\Settings;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Meteostation\Application\Adapters\Meteostation\DataLoaderInterface;
use App\Meteostation\Application\Service\Meteostation\Formatter\KnotsFormatter;
use App\Meteostation\Application\Service\Meteostation\Formatter\MeterFormatter;
use App\Meteostation\Application\Service\Meteostation\WindSpeedConverter;
use App\Meteostation\Domain\ValueObject\DataInterface;
use App\Meteostation\Domain\ValueObject\MeteostationData;
use App\Meteostation\Domain\ValueObject\WindDimension\Knots;
use App\Meteostation\Domain\ValueObject\WindDimension\Meters;
use App\Meteostation\Domain\ValueObject\WindSpeed;

class Notifier
{
    private DataLoaderInterface $dataLoader;

    private NotifierInterface $notifier;

    private UserRepositoryInterface $userRepository;

    private WindSpeedConverter $windSpeedConverter;

    public function __construct(DataLoaderInterface $dataLoader, NotifierInterface $notifier, UserRepositoryInterface $userRepository, WindSpeedConverter $windSpeedConverter)
    {
        $this->dataLoader = $dataLoader;
        $this->notifier = $notifier;
        $this->userRepository = $userRepository;
        $this->windSpeedConverter = $windSpeedConverter;
    }

    public function notifyAllUsers(): void
    {
        $resultIndexedSpotId = $this->dataLoader->loadData();
        $users = $this->userRepository->getAll();
        foreach ($users as $user) {
            $userSpots = $user->getSettings()->getSpots();
            if (empty($userSpots)) {
                continue;
            }

            $userSpotsResults = array_intersect_key($resultIndexedSpotId, array_flip($user->getSettings()->getSpots()));
            if (empty($userSpotsResults)) {
                continue;
            }

            foreach ($userSpotsResults as $spotResult) {
                if (!$this->isWindExpected($user, $spotResult)) {
                    continue;
                }
                $message = $this->getExpectedWindMessage($user, $spotResult);
                $this->notifier->notify($user, $message);
            }
        }
    }

    public function isWindExpected(User $user, MeteostationData $meteostationData): bool
    {
        if ($user->getSettings()->getNotificationsSettings() === Settings::EVERY_MINUTE_NOTIFICATIONS_KEY) {
            return true;
        }

        $customNotificationsSettings = $user->getSettings()->getCustomNotificationsSettings();
        if ($customNotificationsSettings === null) {
            $userSettings = $user->getSettings();
            $userSettings->setNotificationsSettings(Settings::EVERY_MINUTE_NOTIFICATIONS_KEY);
            $userSettings->setCustomNotificationsSettings(null);
            $user->setSettings($userSettings);
            $user->save();
            return true;
        }

        $notificationParameter = $user->getSettings()->getNotificationParameter();
        $notificationParameterName = array_search($notificationParameter, Settings::NOTIFICATION_PARAMETERS);
        $getValueHandler = "get{$notificationParameterName}WindSpeed";
        /** @var WindSpeed $currentValue */
        $currentValue = $meteostationData->$getValueHandler();
        $currentDimension = $currentValue->getDimension();

        $savedValue = $customNotificationsSettings->getValue();
        $userDimension = new Meters();
        if ($customNotificationsSettings->getDimension() === CustomNotificationsSettings::KNOTS_DIMENSION) {
            $userDimension = new Knots();
        }
        $preparedValue = $this->windSpeedConverter->convert($savedValue, $userDimension, $currentDimension);

        $formula = "{$currentValue->getValue()} {$customNotificationsSettings->getOperator()} {$preparedValue}";
        $isSuitableResult = eval("return {$formula};");

        return $isSuitableResult;
    }

    public function getExpectedWindMessage(User $user, DataInterface $parserResult): string
    {
        $preparedResult = $parserResult;
        $formatter = $this->getUserFormatter($user);
        if ($formatter !== null) {
            $preparedResult = new $formatter($parserResult);
        }
        return "Спот: {$preparedResult->getSpot()->getName()}\n{$preparedResult}";
    }

    /**
     * @param User $user
     * @return class-string|null
     */
    private function getUserFormatter(User $user): ?string
    {
        if ($user->getSettings()->getReportFormat() === Settings::FULL_REPORT_FORMAT_KEY) {
            return null;
        }
        $formatter = MeterFormatter::class;
        if ($user->getSettings()->getReportFormat() === Settings::KNOTS_REPORT_FORMAT_KEY) {
            $formatter = KnotsFormatter::class;
        }
        return $formatter;
    }
}
