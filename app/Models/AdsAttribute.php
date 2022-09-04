<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CustomAttributes;
use App\Traits\CustomActions;
use App\Traits\JalaliDate;

class AdsAttribute extends Model
{
    use softDeletes, CustomActions, CustomAttributes, JalaliDate;

    protected $table = 'ads_attributes';
    protected $guarded = [];

    protected $appends = [
        'value'
    ];

    public function ads()
    {
        return $this->belongsTo(Ads::class, 'ads_id');
    }

    public function description()
    {
        return $this->belongsTo(AdsAttributeDescription::class, 'ads_attribute_description_id');
    }

    public function getValueAttribute()
    {
        return $this->description->adsAttributeDescriptionValueType->title == 'string' ? $this->string_value : $this->integer_value;
    }

}
