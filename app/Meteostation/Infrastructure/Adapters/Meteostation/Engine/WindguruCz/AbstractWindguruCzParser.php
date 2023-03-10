<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\WindguruCz;

use App\Meteostation\Domain\ValueObject\DataInterface;
use App\Meteostation\Domain\ValueObject\MeteostationData;
use App\Meteostation\Domain\ValueObject\WindSpeed;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\AbstractParser;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\OneChip\Exception\ServiceUnavailable;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

abstract class AbstractWindguruCzParser extends AbstractParser
{

    private const BASE_DATA_URL = 'https://www.windguru.net/int/iapi.php';

    protected string $_spotId;

    public function __construct(string $spotId)
    {
        parent::__construct(self::BASE_DATA_URL);
        $modifiedSince = (new \DateTime('-1 minutes'))->setTimezone(new \DateTimeZone('UTC'));
        $modifiedSinceInFormat = $modifiedSince->format('D, d M Y H:i:s e');
        $this->_httpClient = new Client([
            RequestOptions::HEADERS => [
                'Accept' => '*/*',
                'Accept-Language' => 'ru-RU,ru;q=0.9',
                'Cookie' => 'langc=ru-; deviceid=478e36b457ef621aa39ec83fb7b3cd67; wgcookie=1|||||||||s3661||||0|39_0|0||||||||; session=d2017f76da547956f90be37d29943253',
                'Connection' => 'keep-alive',
                'Host' => 'www.windguru.cz',
                'If-Modified-Since' => "{$modifiedSinceInFormat}",
                'Referer' => 'https://www.windguru.cz/station/3661',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36',
            ]
        ]);
        $this->_spotId = $spotId;
    }

    public function getData(): DataInterface
    {
        $response = $this->_httpClient->get($this->buildSpotUrl());
        $data = json_decode($response->getBody(), true);
        if ($data === null || !array_key_exists('wind_min', $data)) {
            throw new ServiceUnavailable('Can\'t load data from windguru.cz');
        }
        $timestamp = $data['unixtime'];
        $windDirection = $this->_converter->convertDegreesToWindDirection($data['wind_direction']);
        $temperature = $data['temperature'];
        return new MeteostationData(
            Carbon::createFromFormat('U', $timestamp),
            WindSpeed::buildKnots($data['wind_min']),
            WindSpeed::buildKnots($data['wind_max']),
            WindSpeed::buildKnots($data['wind_avg']),
            $windDirection,
            $temperature,
        );
    }

    protected function buildSpotUrl(): string
    {
        $params = [
            'q' => 'station_data_current',
            'id_station' => $this->_spotId,
            'date_format' => 'Y-m-d H:i:s T'
        ];
        return self::BASE_DATA_URL . '?' . http_build_query($params);
    }
}
