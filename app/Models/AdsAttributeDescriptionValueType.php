<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CustomAttributes;
use App\Traits\CustomActions;
use App\Traits\JalaliDate;

class AdsAttributeDescriptionValueType extends Model
{
    use softDeletes, CustomActions, CustomAttributes, JalaliDate;

    protected $table = 'ads_attribute_description_value_types';

    public function adsAttributeDescriptions()
    {
        return $this->hasMany(AdsAttributeDescription::class, 'ads_attribute_description_value_type_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
