<?php

namespace App\Models;


use App\Traits\CustomActions;
use App\Traits\CustomAttributes;
use App\Traits\JalaliDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use softDeletes, CustomAttributes, CustomActions, JalaliDate;
    protected $guarded = [];

    public function seenAdmin()
    {
        return $this->belongsTo(User::class, 'seen_admin_id');
    }

    public function publishedAdmin()
    {
        return $this->belongsTo(User::class, 'published_admin_id');
    }

    public function getBlogComments()
    {
        return $this->whereCommentableType(Blog::class);
    }

    public function getAdsComments()
    {
        return $this->whereCommentableType(Ads::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished($query)
    {
        $query->whereNotNull('published_at');
    }

}
