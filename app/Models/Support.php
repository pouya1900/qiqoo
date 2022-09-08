<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CustomAttributes;
use App\Traits\CustomActions;
use App\Traits\JalaliDate;

class Support extends Model
{
    use softDeletes, CustomActions, CustomAttributes, JalaliDate;

    public static $rules = [
        'title'  => 'required|string|min:3|max:250',
        'text' => 'required|string|max:2000|min:6',
    ];

    protected $fillable = [
        'name', 'mobile', 'email', 'title', 'text'
    ];

    public function seenAdmin()
    {
        return $this->belongsTo(User::class, 'seen_admin_id');
    }
}
