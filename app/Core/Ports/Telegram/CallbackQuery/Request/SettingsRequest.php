<?php

namespace App\Core\Ports\Telegram\CallbackQuery\Request;

class SettingsRequest implements RequestInterface
{

    private string $_type;

    private string $_parameter;

    /** @var mixed */
    private $_data;

    /** @var mixed */
    private $_value;

    public function __construct(string $type, $data, string $parameter, $value)
    {
        $this->_type = $type;
        $this->_data = $data;
        $this->_parameter = $parameter;
        $this->_value = $value;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @return string
     */
    public function getParameter(): string
    {
        return $this->_parameter;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }

}
