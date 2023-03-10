<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Windy;

use App\Meteostation\Domain\Entity\Spot;
use App\Meteostation\Domain\ValueObject\DataInterface;
use App\Meteostation\Domain\ValueObject\MeteostationData;
use App\Meteostation\Domain\ValueObject\WindSpeed;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\AbstractParser;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\GetDataAsyncInterface;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Exception\ServiceUnavailable;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;

abstract class AbstractWindyParser extends AbstractParser implements GetDataAsyncInterface
{

    private const BASE_DATA_URL = 'https://windyapp.co/apiV9.php';

    protected string $_spotId;

    public function __construct(string $spotId)
    {
        parent::__construct(self::BASE_DATA_URL);
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
            ]
        ]);
        $this->_spotId = $spotId;
    }

    public function getData(): DataInterface
    {
        $response = $this->_httpClient->get($this->buildSpotUrl());
        $data = json_decode($response->getBody(), true);
        $lastItem = count($data['response']['data']) - 1;
        $lastDataItem = $data['response']['data'][$lastItem] ?? null;
        if ($lastDataItem === null) {
            throw new ServiceUnavailable('Can\'t load data from windy');
        }

        $timestamp = $lastDataItem['timestamp'];
        $minWindSpeed = $lastDataItem['wind_min'];
        $avgWindSpeed = $lastDataItem['wind_avg'];
        $maxWindSpeed = $lastDataItem['wind_max'];
        $windDirectionDegrees = $lastDataItem['wind_direction'];
        $windDirection = $this->_converter->convertDegreesToWindDirection($windDirectionDegrees);
        $temperature = $lastDataItem['temperature'];
        return new MeteostationData(
            Carbon::createFromFormat('U', $timestamp),
            WindSpeed::buildMeters($minWindSpeed),
            WindSpeed::buildMeters($maxWindSpeed),
            WindSpeed::buildMeters($avgWindSpeed),
            $windDirection,
            $temperature,
        );
    }

    public function getDataAsync(): PromiseInterface
    {
        $response = $this->_httpClient->getAsync($this->buildSpotUrl());
        return $response->then(function ($response) {
            $data = json_decode($response->getBody(), true);
            $lastItem = count($data['response']['data']) - 1;
            $lastDataItem = $data['response']['data'][$lastItem] ?? null;
            if ($lastDataItem === null) {
                throw new ServiceUnavailable('Can\'t load data from windy');
            }

            $timestamp = $lastDataItem['timestamp'];
            $minWindSpeed = $lastDataItem['wind_min'];
            $avgWindSpeed = $lastDataItem['wind_avg'];
            $maxWindSpeed = $lastDataItem['wind_max'];
            $windDirectionDegrees = $lastDataItem['wind_direction'];
            $windDirection = $this->_converter->convertDegreesToWindDirection($windDirectionDegrees);
            $temperature = $lastDataItem['temperature'];
            return new MeteostationData(
                Carbon::createFromFormat('U', $timestamp),
                WindSpeed::buildMeters($minWindSpeed),
                WindSpeed::buildMeters($maxWindSpeed),
                WindSpeed::buildMeters($avgWindSpeed),
                $windDirection,
                $temperature,
            );
        });
    }

    protected function buildSpotUrl(): string
    {
        $params = [
            'method' => 'getMeteostationDataAndInfo',
            'meteostationID' => $this->_spotId
        ];
        return self::BASE_DATA_URL . '?' . http_build_query($params);
    }
}
