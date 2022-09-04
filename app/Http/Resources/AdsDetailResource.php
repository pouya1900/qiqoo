<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdsDetailResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description ?? '',
            'type' => $this->type ?? 1,
            'scoreAverage' => $this->scoreAverage ?? 0,
            'publishedCommentCount' => $this->publishedCommentCount ?? 0,
            'scoreCount' => $this->Scores ?? [],
            'viewCount' => $this->viewCount ?? [],
            'zipCode' => $this->postal_code ?? '',
            'user' => [
                'id' => $this->user->id,
                'firstName' => $this->user->first_name ?? '',
                'lastName' => $this->user->last_name ?? '',
                'fullName' => $this->user->full_name ?? '',
                'image' => $this->user->avatar,
            ],
            'category' => [
                'id' => $this->category->id,
                'title' => $this->category->title,
            ],
            'shareLink' => $this->getShareLink(),
            'logo' => $this->logo,
            'contentImages' => $this->contentImages,
            'video_link' => $this->video_link ?? '',
            'phone' => $this->phone ?? '',
            'mobile' => $this->mobile ?? '',
            'website' => $this->website ?? '',
            'instagram' => $this->instagram ?? '',
            'twitter' => $this->twitter ?? '',
            'youtube' => $this->youtube ?? '',
            'facebook' => $this->facebook ?? '',
            'email' => $this->email ?? '',
            'address' => $this->address ?? '',
            'lat' => $this->lat ?? '',
            'lon' => $this->long ?? '',
            'city' => new CityResource($this->city),
            'country' => [
                'id' => $this->country->id,
                'title' => $this->country->title,
            ],
            'comments' => CommentResource::collection($this->publishedComments),
            'startDate' => $this->start_date ?? null,
            'endDate' => $this->end_date ?? null,
            'isBookmark' => isset($this->bookmarks[0]) ? 1 : 0,
            'isImmediate' => $this->is_immediate ?? 0,
            'isVip' => $this->is_vip ?? 0,
            'isTop' => $this->is_top ?? 0,
            'createdAt' => $this->created_at,
            'publishedAt' => $this->published_at,
        ];

    }
}
