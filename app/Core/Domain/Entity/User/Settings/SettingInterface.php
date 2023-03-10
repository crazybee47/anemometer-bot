<?php

namespace App\Core\Domain\Models\User\Settings;

interface SettingInterface extends \JsonSerializable
{
    public function getName(): string;

    public function getKey(): string;

    public function getSelectedValue(): string;

    /**
     * @return SettingValueInterface[]
     */
    public function getAvailableValues(): array;
}
