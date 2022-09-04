<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Profile extends Model
{
    use softDeletes;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'female',
        'lat',
        'long',
        'postal_code',
        'city_id',
        'birthday',
        'address',
        'company',
        'en_company',
        'about',
        'en_about',
        'specialist',
        'en_specialist',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
