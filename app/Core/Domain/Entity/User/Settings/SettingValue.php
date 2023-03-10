<?php

namespace App\Core\Domain\Models\User\Settings;

class SettingValue implements SettingValueInterface
{
    private string $name;

    private string $value;

    /**
     * @param string $key
     * @param string $value
     */
    public function __construct(string $key, string $value)
    {
        $this->name = $key;
        $this->value = $value;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }


    public function jsonSerialize()
    {
        return [
            $this->getName() => $this->getValue()
        ];
    }
}
