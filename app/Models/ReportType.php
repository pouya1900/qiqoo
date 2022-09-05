<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ReportType extends Model
{
    public $max_importance_level = 5;

    public function reports()
    {
        return $this->hasMany(Report::class, 'report_type_id');
    }
}
