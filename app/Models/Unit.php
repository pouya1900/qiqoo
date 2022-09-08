<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CustomAttributes;
use App\Traits\CustomActions;
use App\Traits\JalaliDate;

class Unit extends Model
{
    use softDeletes, CustomActions, CustomAttributes, JalaliDate;

    protected $table = 'units';

    protected $fillable = ['title', 'admin_id'];

    public static $rules = [
        'title' => 'required|string|min:3|max:50'
    ];

    /**
    define scopes
     */
    public function scopeAdmin($query, $admin)
    {
        return $query->where('admin_id', $admin);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function adsAttributeDescriptions()
    {
        return $this->hasMany(AdsAttributeDescription::class, 'unit_id');
    }
}
