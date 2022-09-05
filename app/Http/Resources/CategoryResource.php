<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'showInHome' => $this->show_in_home,
            'colorCode' => $this->color_code ?? '',
            'parentId' => $this->parent_id,
            'logo' => $this->getLogoAttribute(),
            'icon' => $this->getIconAttribute()
        ];
    }
}
