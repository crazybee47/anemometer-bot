<?php

namespace App\Core\DomainNew\Entity;

use App\Core\DomainNew\ValueObject\UserSettings;
use App\Shared\Domain\EntityInterface;

class User implements EntityInterface
{
    private string $id;

    private UserSettings $settings;

    /**
     * @param string $id
     * @param UserSettings $settings
     */
    public function __construct(string $id, UserSettings $settings)
    {
        $this->id = $id;
        $this->settings = $settings;
    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return UserSettings
     */
    public function getSettings(): UserSettings
    {
        return $this->settings;
    }


}
