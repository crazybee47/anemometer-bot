<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine;

use App\Meteostation\Domain\ValueObject\DataInterface;
use App\Meteostation\Infrastructure\Service\WindDirectionConverter;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

abstract class AbstractParser implements EngineInterface
{

    protected string $_baseUrl;

    protected Client $_httpClient;

    protected WindDirectionConverter $_converter;

    public function __construct(string $baseUrl)
    {
        $this->_baseUrl = $baseUrl;
        $this->_httpClient = new Client([
            RequestOptions::HEADERS => [
                'Connection: keep-alive',
                'sec-ch-ua: " Not;A Brand";v="99", "Google Chrome";v="91", "Chromium";v="91"',
                'sec-ch-ua-mobile: ?0',
                'Upgrade-Insecure-Requests: 1',
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36',
                'Sec-Fetch-Site: none',
                'Sec-Fetch-Mode: navigate',
                'Sec-Fetch-User: ?1',
                'Sec-Fetch-Dest: document',
                'Accept-Language: ru-RU,ru;q=0.9',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            ],
        ]);
        $this->_converter = new WindDirectionConverter();
    }

    abstract public function getData(): DataInterface;

    abstract protected function getSpotId(): string;

    abstract protected function getSpotName(): string;

}
