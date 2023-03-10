<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Html;

use App\Meteostation\Domain\Entity\Spot;
use App\Meteostation\Domain\ValueObject\DataInterface;
use App\Meteostation\Domain\ValueObject\MeteostationData;
use App\Meteostation\Domain\ValueObject\WindSpeed;
use Carbon\Carbon;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use PHPHtmlParser\Dom;

class Hurgada extends AbstractHtmlParser
{

    public const SPOT_NAME = 'Хургада';
    public const SPOT_ID = 'egypt_1';

    private const BASE_URL = 'https://windguru.by/amc/';

    private const MAX_WIND_SPEED_COL_INDEX = 0;
    private const AVG_WIND_SPEED_COL_INDEX = 1;
    private const TEMPERATURE_COL_INDEX = 2;

    public function __construct()
    {
        parent::__construct(self::BASE_URL);
    }

    protected function convertDomToParserResult(Dom $dom): DataInterface
    {
        $updatedAtNode = $dom->find('div.cont div.upd');
        if ($updatedAtNode->count() > 0) {
            $updatedAtNode = current($updatedAtNode->toArray());
        }
        /** @var Dom\Node\HtmlNode $updatedAtNode */
        $updatedAt = $updatedAtNode->text;
        preg_match('/(\d+\:\d+:\d+)/m', $updatedAt, $matches);
        if (count($matches) > 0) {
            $updatedAt = new Carbon($matches[0], 'Africa/Cairo');
            $updatedAt = $updatedAt->setTimezone('Europe/Moscow');
        }

        $maxWindSpeed = 0;
        $avgWindSpeed = 0;
        $temperature = 0;
        $dataNodes = $dom->find('div.tabb dt');
        foreach ($dataNodes->toArray() as $index => $node) {
            if ($index === self::MAX_WIND_SPEED_COL_INDEX) {
                $maxWindSpeed = (float)$node->text;
            }
            if ($index === self::AVG_WIND_SPEED_COL_INDEX) {
                $avgWindSpeed = (float)$node->text;
            }
            if ($index === self::TEMPERATURE_COL_INDEX) {
                $temperature = (float)$node->text;
            }
        }
        $minWindSpeed = $avgWindSpeed * 2 - $maxWindSpeed;

        return new MeteostationData(
            $updatedAt,
            WindSpeed::buildKnots($minWindSpeed),
            WindSpeed::buildKnots($maxWindSpeed),
            WindSpeed::buildKnots($avgWindSpeed),
            null,
            $temperature,
        );
    }

    public function getDataAsync(): PromiseInterface
    {
        $promise = new Promise(function () use (&$promise) {
            $dom = (new Dom())->loadFromUrl($this->_baseUrl, null, $this->_httpClient);
            $promise->resolve($dom);
        });

        return $promise->then(function ($dom) {
            $updatedAtNode = $dom->find('div.cont div.upd');
            if ($updatedAtNode->count() > 0) {
                $updatedAtNode = current($updatedAtNode->toArray());
            }
            /** @var Dom\Node\HtmlNode $updatedAtNode */
            $updatedAt = $updatedAtNode->text;
            preg_match('/(\d+\:\d+:\d+)/m', $updatedAt, $matches);
            if (count($matches) > 0) {
                $updatedAt = new Carbon($matches[0], 'Africa/Cairo');
                $updatedAt = $updatedAt->setTimezone('Europe/Moscow');
            }

            $maxWindSpeed = 0;
            $avgWindSpeed = 0;
            $temperature = 0;
            $dataNodes = $dom->find('div.tabb dt');
            foreach ($dataNodes->toArray() as $index => $node) {
                if ($index === self::MAX_WIND_SPEED_COL_INDEX) {
                    $maxWindSpeed = (float)$node->text;
                }
                if ($index === self::AVG_WIND_SPEED_COL_INDEX) {
                    $avgWindSpeed = (float)$node->text;
                }
                if ($index === self::TEMPERATURE_COL_INDEX) {
                    $temperature = (float)$node->text;
                }
            }
            $minWindSpeed = $avgWindSpeed * 2 - $maxWindSpeed;

            return new MeteostationData(
                $updatedAt,
                WindSpeed::buildKnots($minWindSpeed),
                WindSpeed::buildKnots($maxWindSpeed),
                WindSpeed::buildKnots($avgWindSpeed),
                null,
                $temperature,
            );
        });
    }

    protected function getSpotId(): string
    {
        return self::SPOT_ID;
    }

    protected function getSpotName(): string
    {
        return self::SPOT_NAME;
    }
}
