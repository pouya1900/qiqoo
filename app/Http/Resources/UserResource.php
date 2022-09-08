<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'mobile' => $this->mobile,
            'mobileVisibility' => intval($this->mobile_visibility),
            'email' => $this->email ?? '',
            'firstName' => $this->first_name ?? '',
            'lastName' => $this->last_name ?? '',
            'fullName' => $this->full_name ?? '',
            'image' => $this->avatar,
            'profile' => !empty($this->profile) ?  new ProfileResource($this->profile) : null,
        ];
    }
}
