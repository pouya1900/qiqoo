<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdsShortResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param Request $request
	 *
	 * @return array
	 */
	public function toArray( $request ) {
		return [
			'id'           => $this->id,
			'title'        => $this->title,
			'description'  => $this->description ?? '',
			'type' => $this->type ?? 1,
			'scoreAverage' => $this->scoreAverage ?? 0,
			'logo'                  => $this->logo,
			'city' => new CityResource($this->city),
			'lat' => $this->lat ?? 0,
			'lon' => $this->long ?? 0,
			'address' => $this->address ?? 0,
			'zipCode' => $this->postal_code ?? '',
			'shareLink' => $this->getShareLink(),
		];


	}
}
