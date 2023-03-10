<?php

namespace App\Core\Domain\Models;

use App\Core\Domain\Models\User\Settings;
use App\Core\Infrastructure\Service\UserService;
use App\Meteostation\Domain\Entity\Spot;

class User
{

    private int $_id;

    private ?Settings $_settings;

    public function __construct(int $id, ?Settings $settings = null)
    {
        $this->_id = $id;
        $this->_settings = $settings;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->_id;
    }

    /**
     * @return Settings
     */
    public function getSettings(): Settings
    {
        if ($this->_settings !== null) {
            return $this->_settings;
        }

        return new Settings();
    }

    /**
     * @param Settings $settings
     */
    public function setSettings(Settings $settings): void
    {
        $this->_settings = $settings;
    }

    public function save(): void
    {
        (new UserService())->saveUser($this);
    }

    public function isSubscribedToSpot(Spot $spot): bool
    {
        return in_array($spot->getId(), $this->getSettings()->getSpots());
    }
}
