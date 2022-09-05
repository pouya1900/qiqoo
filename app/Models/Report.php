<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CustomActions;
use App\Traits\CustomAttributes;
use App\Traits\JalaliDate;


class Report extends Model
{
    use softDeletes, CustomActions, CustomAttributes, JalaliDate;

    protected $fillable = [
        'name', 'email', 'mobile', 'text', 'ads_id', 'report_type_id', 'seen_admin_id', 'seen_at'
    ];

    public function getReportableNameAttribute()
    {
        return $this->reportable_type == Blog::class ? 'اخبار' : 'آگهی';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seenAdmin()
    {
        return $this->belongsTo(User::class, 'seen_admin_id');
    }

    public function reportType()
    {
        return $this->belongsTo(ReportType::class, 'report_type_id');
    }

    public function reportable()
    {
        return $this->morphTo();
    }
}
