<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
            'logo' => $this->logo,
            'shortDescription' => $this->short_description ?? '',
            'publishedCommentCount' => $this->publishedCommentCount ?? 0,
            'video'         => $this->contentVideo,
            'isVideo'              => intval($this->is_video),
            'shareLink' => $this->getShareLink(),
            'category' => [
                'id' => $this->category->id,
                'title' => $this->category->title,
            ],
            'sliderBlog' => $this->slider_blog ?? 0,
            'createdAt' => $this->created_at,
            'publishedAt' => $this->published_at,
        ];

    }
}
