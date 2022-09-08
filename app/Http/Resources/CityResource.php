<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title ?? '',
            'enTitle' => $this->en_title ?? '',
            'lat' => $this->lat,
            'lon' => $this->long,
            'country' => [
                'isoCode3' => $this->country->iso_code3,
                'flagLink' => $this->country->flagLink
            ]
        ];
    }
}
