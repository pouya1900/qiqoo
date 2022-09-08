<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
	    return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description ?? '',
            'type' => $this->type ?? 1,
            'scoreAverage' => $this->scoreAverage ?? 0,
            'publishedCommentCount' => $this->publishedCommentCount ?? 0,
            'zipCode' => $this->postal_code ?? '',
            'shareLink' => $this->getShareLink(),
            'lat' => $this->lat ?? 0,
            'lon' => $this->long ?? 0,
            'contentImagesCount' => $this->contentImages()->count(),
            'category' => [
                'id' => $this->category->id,
                'title' => $this->category->title,
            ],
            'logo' => $this->logo,
            'city' => new CityResource($this->city),
            'isBookmark' => isset($this->bookmarks[0]) ? 1 : 0,
            'isImmediate' => $this->is_immediate ?? 0,
            'isVip' => $this->is_vip ?? 0,
            'isTop' => $this->is_top ?? 0,
            'createdAt' => $this->created_at,
            'publishedAt' => $this->published_at,
        ];

    }
}
