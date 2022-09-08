<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogCategoryResource extends JsonResource
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
            'colorCode' => $this->color_code,
            'isFavorite' => $this->is_favorite ? $this->is_favorite : 0,
            'order' => $this->order,
            'created_at' => $this->created_at,
            'icon' => $this->icon
        ];

    }
}
