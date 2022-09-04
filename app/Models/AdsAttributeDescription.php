<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CustomAttributes;
use App\Traits\CustomActions;
use App\Traits\JalaliDate;

class AdsAttributeDescription extends Model
{
    use softDeletes, CustomActions, CustomAttributes, JalaliDate;

    protected $table = 'ads_attribute_descriptions';
    // protected $fillable = ['title', 'en_title', 'is_public', 'admin_id', 'ads_attribute_description_value_type_id'];
    public static $rules = [
        'title' => 'required|string|min:3|max:100',
        'field_name' => 'required|min:2|max:10',
        'unit_id' => 'nullable|integer|exists:units,id',
        'ads_attribute_description_value_type_id' => 'required|integer|exists:ads_attribute_description_value_types,id',
        'category_id' => 'required|array'
    ];

    protected $guarded = ['category_id'];
    /**
    define scopes
     */
    public function scopeAdmin($query, $admin)
    {
        return $query->where('admin_id', $admin);
    }

    public function categories()
    {
        return $this->morphToMany(AdsCategory::class, 'categoriable', '','', 'category_id');
    }

    public function adsAttributeDescriptionValueType()
    {
        return $this->belongsTo(AdsAttributeDescriptionValueType::class, 'ads_attribute_description_value_type_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
