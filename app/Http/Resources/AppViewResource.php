<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppViewResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param Request $request
	 *
	 * @return array
	 */
	public function toArray( $request ) {

		$return = [
			'id'                 => $this->id,
			'type'               => [
				'id'      => $this->appViewType->id,
				'title'   => $this->appViewType->type_title,
				'titleFa' => $this->appViewType->type_title_fa,
				'content' => $this->appViewType->type_content
			],
			'title'              => $this->title,
			'titleColor'         => $this->title_color_code,
			'description'        => $this->description,
			'descriptionColor'   => $this->description_color_code,
			'action'             => $this->action,
			'actionColor'        => $this->action_color_code,
			'needSpace'          => $this->need_space ? 1 : 0,
			'backgroundImage'    => $this->backgroundImage,
			'backgroundColor'    => $this->background_color_code,
			'hasBackgroundImage' => $this->backgroundImage['id'] ? 1 : 0,
			'createdAt'          => $this->created_at,
			'publishedAt'        => $this->published_at,
		];

		if ( $this->appViewType->type_content == "adsCategory" ) {
			$return['categories'] = CategoryResource::collection( $this->getAdsCategoriesPaginate( $request->number ) );
		} else if ( $this->appViewType->type_content == "ads" ) {
			$return['ads'] = AdsResource::collection( $this->getAdsPaginate( $request->number ) );
		}


		return $return;

	}
}
