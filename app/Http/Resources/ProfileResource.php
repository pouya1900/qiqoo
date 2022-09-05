<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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

            'female' => intval($this->female) ?? 0,
            'cityId' => $this->city->id ?? 0,
            'cityTitle' => $this->city->title ?? '',
            'cityEnTitle' => $this->city->en_title ?? '',
            'countryId' => $this->city->country->id ?? '',
            'zipCode' => $this->postal_code ?? '',
            'countryTitle' => $this->city->country->title ?? '',
            'countryEnTitle' => $this->city->country->en_title ?? '',
            'birthday' => $this->birthday ?? null,
            'address' => $this->address ?? '',
            'lat' => $this->lat ?? 0 ,
            'lon' => $this->long ?? 0,
            'company' => $this->company ?? '',
            'enCompany' => $this->en_company ?? '',
            'about' => $this->about ?? '',
            'enAbout' => $this->en_about ?? '',
            'specialist' => $this->specialist ?? '',
            'enSpecialist' => $this->en_specialist ?? '',
            'facebook' => $this->facebook ?? '',
            'twitter' => $this->twitter ?? '',
            'linkedin' => $this->linkedin ?? '',
            'instagram' => $this->instagram ?? '',
            'createdAt' => $this->created_at ?? ''
        ];

    }
}
