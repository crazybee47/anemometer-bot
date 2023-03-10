<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Html;

use App\Meteostation\Domain\Entity\Spot;
use App\Meteostation\Domain\ValueObject\DataInterface;
use App\Meteostation\Domain\ValueObject\MeteostationData;
use App\Meteostation\Domain\ValueObject\WindSpeed;
use PHPHtmlParser\Dom;

class TochkaOtriva extends AbstractHtmlParser
{

    private const BASE_URL = 'https://tochka-otriva.com/prognoz-vetra-mezhvodnoe/';

    public function __construct()
    {
        parent::__construct(self::BASE_URL);
    }

    protected function convertDomToParserResult(Dom $dom): DataInterface
    {
        $windSpeedElement = $dom->find('.l-subheader span.lws-text');
        $preparedWindSpeed = null;
        $windDirection = null;
        if (count($windSpeedElement) > 0) {
            $preparedWindSpeed = (float)str_replace(['г.', " "], '', html_entity_decode($windSpeedElement[0]->text));
        }
        $windDirectionElement = $dom->find('.l-subheader i.wi-wind');
        if (count($windDirectionElement) > 0) {
            $windDirectionClasses = $windDirectionElement[0]->getAttribute('class');
            if (strlen($windDirectionClasses) > 0) {
                $windDirectionClasses = explode(' ', $windDirectionClasses);
                $windDirectionClass = array_filter($windDirectionClasses, fn(string $class
                ) => strpos($class, 'towards') !== false);
                if (count($windDirectionClass) > 0) {
                    $windDirectionClass = max($windDirectionClass);
                    $windDirectionDegrees = 0;
                    $re = '/towards\-(\d+)\-deg/mu';
                    preg_match_all($re, $windDirectionClass, $matches, PREG_SET_ORDER, 0);
                    if (count($matches) > 0) {
                        $windDirectionDegrees = (int)$matches[0][1];
                    }
                    $windDirection = $this->_converter->convertDegreesToWindDirection($windDirectionDegrees);
                }
            }
        }

        return new MeteostationData(
            now(),
            WindSpeed::buildMeters($preparedWindSpeed),
            WindSpeed::buildMeters($preparedWindSpeed),
            WindSpeed::buildMeters($preparedWindSpeed),
            $windDirection,
            null,
        );
    }

    protected function getSpotId(): string
    {
        return 'tochka_otriva';
    }

    protected function getSpotName(): string
    {
        return 'Межводное (Точка отрыва)';
    }
}
