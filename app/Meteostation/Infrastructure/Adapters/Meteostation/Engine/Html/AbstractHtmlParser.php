<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\Html;

use App\Meteostation\Domain\ValueObject\DataInterface;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\AbstractParser;
use App\Meteostation\Infrastructure\Adapters\Meteostation\Engine\GetDataAsyncInterface;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use PHPHtmlParser\Dom;

abstract class AbstractHtmlParser extends AbstractParser implements GetDataAsyncInterface
{

    public function __construct(string $baseUrl)
    {
        parent::__construct($baseUrl);
    }

    public function getData(): DataInterface
    {
        $dom = (new Dom())->loadFromUrl($this->_baseUrl, null, $this->_httpClient);
        return $this->convertDomToParserResult($dom);
    }

    public function getDataAsync(): PromiseInterface
    {
        $promise = new Promise(function () use (&$promise) {
            $dom = (new Dom())->loadFromUrl($this->_baseUrl, null, $this->_httpClient);
            $promise->resolve($dom);
        });
        return $promise->then(
            fn($dom) => $this->convertDomToParserResult($dom),
            fn($error) => null
        );
    }

    abstract protected function convertDomToParserResult(Dom $dom): DataInterface;
}
