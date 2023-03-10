<?php

namespace App\Meteostation\Infrastructure\Service;

class WindDirectionConverter
{
    public function convertDegreesToWindDirection(int $degrees): ?string
    {
        $compass = [
            'Ю' => [
                'from' => 360,
                'to' => 0,
            ],
            'ЮЮЗ' => [
                'from' => 1,
                'to' => 44,
            ],
            'ЮЗ' => [
                'from' => 45,
                'to' => 45,
            ],
            'ЗЮЗ' => [
                'from' => 46,
                'to' => 89,
            ],
            'З' => [
                'from' => 90,
                'to' => 90,
            ],
            'ЗСЗ' => [
                'from' => 91,
                'to' => 134,
            ],
            'СЗ' => [
                'from' => 135,
                'to' => 135,
            ],
            'ССЗ' => [
                'from' => 136,
                'to' => 179,
            ],
            'С' => [
                'from' => 180,
                'to' => 180,
            ],
            'ССВ' => [
                'from' => 181,
                'to' => 254,
            ],
            'СВ' => [
                'from' => 255,
                'to' => 255,
            ],
            'ВСВ' => [
                'from' => 256,
                'to' => 269,
            ],
            'В' => [
                'from' => 270,
                'to' => 270,
            ],
            'ВЮВ' => [
                'from' => 271,
                'to' => 314,
            ],
            'ЮВ' => [
                'from' => 315,
                'to' => 315,
            ],
            'ЮЮВ' => [
                'from' => 316,
                'to' => 359,
            ],
        ];

        foreach ($compass as $direction => $range) {
            if ($degrees >= $range['from'] && $degrees <= $range['to']) {
                return $direction;
            }
        }
        return null;
    }
}
