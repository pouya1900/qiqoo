<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AppViewType extends Model
{

	public function appViews()
	{
		return $this->hasMany(AppView::class, 'type');
	}
}
