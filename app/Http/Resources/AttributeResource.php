<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            'fieldName' => $this->field_name,
            'unitTitle' => !empty($this->unit) ? $this->unit->title : '',
            'attributeType' => [
                'title' => $this->adsAttributeDescriptionValueType->title,
                'inputType' => $this->adsAttributeDescriptionValueType->input_type
            ]
        ];
    }
}
