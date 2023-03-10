<?php

namespace App\Meteostation\Ports\Message;

use Illuminate\Foundation\Http\FormRequest;

class GetDataRequest extends FormRequest
{
    public function rules()
    {
        return [
            'spot_id' => 'required'
        ];
    }

    public function getSpotId(): string
    {
        return $this->get('spot_id');
    }
}
