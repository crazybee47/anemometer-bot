<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Html;

use App\Meteostation\Domain\Entity\Spot;
use App\Meteostation\Domain\ValueObject\DataInterface;
use App\Meteostation\Domain\ValueObject\MeteostationData;
use App\Meteostation\Domain\ValueObject\WindSpeed;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Exception\UndefinedParserData;
use Carbon\Carbon;
use PHPHtmlParser\Dom;

class WindExtreme extends AbstractHtmlParser
{

    public const SPOT_NAME = 'Межводное';
    public const SPOT_ID = '1';

    private const BASE_URL = 'https://www.wind-extreme.com/meteo/wind-mezhvodnoe/index.php';
    private const IMAGE_URL = 'https://www.wind-extreme.com/meteo/wind-mezhvodnoe/grafkz.php';

    private const MIN_WIND_SPEED_COL_NAME = 'Скорость ветра, мин.';
    private const MAX_WIND_SPEED_COL_NAME = 'Скорость ветра, мах.';
    private const AVG_WIND_SPEED_COL_NAME = 'Скорость ветра, ср.';
    private const WIND_DIRECTION_COL_NAME = 'Направление ветра';
    private const TEMPERATURE_COL_NAME = 'Температура воздуха, °С';
    private const DATE_COL_NAME = 'Дата';
    private const TIME_COL_NAME = 'Время';

    public function __construct()
    {
        parent::__construct(self::BASE_URL);
    }

    protected function convertDomToParserResult(Dom $dom): DataInterface
    {
        $headerRowNodes = $dom->find('table.data tr b');
        $headerCols = array_map(fn($node) => $node->text, $headerRowNodes->toArray());
        $data = [];
        $dataColNodes = $dom->find('table.data tr span');
        $dataRows = array_chunk($dataColNodes->toArray(), count($headerCols) + 1);
        foreach ($dataRows as $rowIndex => $dataCols) {
            $isNeedDecreaseColIndex = false;
            foreach ($dataCols as $colIndex => $dataCol) {
                $hasImage = $dataCol->find('img')[0] !== null ? true : false;
                if ($hasImage) {
                    $isNeedDecreaseColIndex = true;
                    continue;
                }
                $preparedColIndex = $colIndex;
                if ($isNeedDecreaseColIndex) {
                    $preparedColIndex = $preparedColIndex - 1;
                }
                $headerCol = $headerCols[$preparedColIndex];
                $value = $dataCol->text;
                $data[$rowIndex][$headerCol] = $value;
            }
        }
        if (!array_key_exists(0, $data)) {
            \Log::error('Can\'t parse data from windextreme.', ['data' => $data]);
            throw new UndefinedParserData('Can\'t parse data from windextreme.');
        }
        $lastMinuteData = $data[0];
        return $this->convertDataToResult($lastMinuteData);
    }

    private function convertDataToResult(array $data): DataInterface
    {
        $months = [
            'января' => 'january',
            'февраля' => 'february',
            'марта' => 'march',
            'апреля' => 'april',
            'мая' => 'may',
            'июня' => 'june',
            'июля' => 'july',
            'августа' => 'august',
            'сентября' => 'september',
            'октября' => 'october',
            'ноября' => 'november',
            'декабря' => 'december',
        ];
        $preparedDate = str_replace(['г.', " "], '', html_entity_decode($data[self::DATE_COL_NAME]));
        foreach ($months as $ruMonth => $engMonth) {
            $preparedDate = str_replace($ruMonth, $engMonth, mb_strtolower($preparedDate));
        }
        $time = $data[self::TIME_COL_NAME];
        $dateTime = new Carbon("{$preparedDate} {$time}");
        return new MeteostationData(
            $dateTime,
            WindSpeed::buildMeters((float)$data[self::MIN_WIND_SPEED_COL_NAME]),
            WindSpeed::buildMeters((float)$data[self::MAX_WIND_SPEED_COL_NAME]),
            WindSpeed::buildMeters((float)$data[self::AVG_WIND_SPEED_COL_NAME]),
            $data[self::WIND_DIRECTION_COL_NAME],
            (float)$data[self::TEMPERATURE_COL_NAME],
        );
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
