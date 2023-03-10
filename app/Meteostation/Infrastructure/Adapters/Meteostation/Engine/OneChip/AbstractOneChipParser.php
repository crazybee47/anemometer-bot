<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip;

use App\Meteostation\Domain\Entity\Spot;
use App\Meteostation\Domain\ValueObject\DataInterface;
use App\Meteostation\Domain\ValueObject\MeteostationData;
use App\Meteostation\Domain\ValueObject\WindSpeed;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\AbstractParser;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\GetDataAsyncInterface;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Exception\ServiceUnavailable;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Exception\UnexpectedDataFormat;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;

abstract class AbstractOneChipParser extends AbstractParser implements GetDataAsyncInterface
{

    private const BASE_ONE_CHIP_DATA_URL = 'http://1chip.ru/get_data_1chip_v002.php';

    protected string $_spotId;

    public function __construct(string $spotId)
    {
        parent::__construct(self::BASE_ONE_CHIP_DATA_URL);
        $this->_httpClient = new Client([
            RequestOptions::HEADERS => [
                'Host: 1chip.ru',
                "Referer: http://1chip.ru/wind0.html?id={$spotId}",
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36',
                'Accept-Language: ru',
                'Accept: text/plain, */*; q=0.01',
                'Connection: keep-alive',
                'Accept-Encoding: gzip, deflate',
                'Cookie: 182b7c8b8aa68e2e73c8264dbf5157e1=a3f260bc4b964ded1cc72b38d7fa3582; __utmc=222142296; __utmz=222142296.1656669888.2.2.utmcsr=yandex.ru|utmccn=(referral)|utmcmd=referral|utmcct=/; graph=2%20hours%20graph; __gads=ID=c9f76f3d2b5d245c-223cf52acbcd000f:T=1657711287:RT=1657711287:S=ALNI_MZJFk4s2hcfxglxUIqDPF3isp8-7w; __gpi=UID=000008a37eb73118:T=1657711287:RT=1657711287:S=ALNI_MbCRUexiSiWtiJiJm7r_pkyidbgyQ; user_user_id=0243; __utma=222142296.1161122799.1656407479.1659608451.1660742121.5; __utmt=1; __utmb=222142296.2.10.1660742121',
                'X-Requested-With: XMLHttpRequest'
            ],
        ]);
        $this->_spotId = $spotId;
    }

    public function getData(): DataInterface
    {
        $response = $this->_httpClient->get($this->buildSpotUrl());
        $data = json_decode($response->getBody(), true);
        if (count($data['data']) === 0) {
            throw new ServiceUnavailable('Can\'t load data from 1chip.ru');
        }
        $lastDataItem = max($data['data']);
        if (count($lastDataItem) !== 6) {
            throw new UnexpectedDataFormat('Unavailable data format from 1chip.ru');
        }
        [$timestamp, $minWindSpeed, $avgWindSpeed, $maxWindSpeed, $windDirectionDegrees, $temperature] = $lastDataItem;
        $windDirection = $this->_converter->convertDegreesToWindDirection($windDirectionDegrees);

        return new MeteostationData(
            Carbon::createFromFormat('U', $timestamp),
            WindSpeed::buildMeters($minWindSpeed),
            WindSpeed::buildMeters($maxWindSpeed),
            WindSpeed::buildMeters($avgWindSpeed),
            $windDirection,
            null,
        );
    }

    public function getDataAsync(): PromiseInterface
    {
        $response = $this->_httpClient->getAsync($this->buildSpotUrl());
        return $response->then(function ($response) {
            $data = json_decode($response->getBody(), true);
            if (count($data['data']) === 0) {
                throw new ServiceUnavailable('Can\'t load data from 1chip.ru');
            }
            $lastDataItem = max($data['data']);
            if (count($lastDataItem) !== 6) {
                throw new UnexpectedDataFormat('Unavailable data format from 1chip.ru');
            }
            [$timestamp, $minWindSpeed, $avgWindSpeed, $maxWindSpeed, $windDirectionDegrees, $temperature] = $lastDataItem;
            $windDirection = $this->_converter->convertDegreesToWindDirection($windDirectionDegrees);

            return new MeteostationData(
                Carbon::createFromFormat('U', $timestamp),
                WindSpeed::buildMeters($minWindSpeed),
                WindSpeed::buildMeters($maxWindSpeed),
                WindSpeed::buildMeters($avgWindSpeed),
                $windDirection,
                null,
            );
        });
    }

    protected function buildSpotUrl(): string
    {
        $date = (new \DateTime('today'))->modify('-3 hours');
        $lastTimestamp = $date->getTimestamp() - 168 * 60 * 60;

        $params = [
            'last_timestamp' => $lastTimestamp,
            'id' => $this->_spotId
        ];
        return self::BASE_ONE_CHIP_DATA_URL . '?' . http_build_query($params);
    }
}
