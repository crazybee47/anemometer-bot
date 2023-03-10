<?php

namespace App\Meteostation\Infrastructure\Adapters\Meteostation\Engine;

use GuzzleHttp\Promise\PromiseInterface;

interface GetDataAsyncInterface
{

    public function getDataAsync(): PromiseInterface;

}
