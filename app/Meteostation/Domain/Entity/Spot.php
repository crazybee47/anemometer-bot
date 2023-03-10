<?php

namespace App\Meteostation\Domain\Entity;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model implements SpotInterface
{
    protected $table = 'meteostation_spots';

    protected $fillable = [
        'external_id',
        'name',
        'timezone',
    ];

    public function getId(): string
    {
        return $this->external_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }
}
