<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $table = 'viewable';

	protected $fillable = ['user_id','uuid','platform','model','os'];

    public function viewable()
    {
        return $this->morphTo();
    }
}
