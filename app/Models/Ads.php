<?php

namespace App\Models;

use App\Traits\ImageUtilsTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CustomAttributes;
use App\Traits\CustomActions;
use App\Traits\JalaliDate;
use Illuminate\Support\Facades\DB;

class Ads extends Model
{
    use softDeletes, CustomActions, CustomAttributes, JalaliDate;
    use ImageUtilsTrait;



    const standard=1;
    const employment=2;

    // **************************************** properties ****************************************
    protected $fillable = ['user_id', 'title', 'en_title', 'description', 'type' , 'en_description', 'meta_title', 'meta_keywords', 'meta_description', 'meta_author', 'category_id', 'video_link', 'phone', 'email', 'mobile', 'website' , 'facebook', 'instagram', 'twitter', 'youtube', 'address', 'en_address', 'city_id', 'postal_code',
        'code', 'lat', 'long', 'start_date', 'end_date', 'last_end_date', 'is_extended', 'is_immediate', 'is_vip', 'is_top', 'paid_price', 'paid_at', 'is_free', 'pay_id', 'extended_time', 'view', 'like', 'comment', 'score', 'published_admin_id', 'published_at', 'seen_admin_id', 'seen_at', 'deleted_at', 'created_at', 'updated_at'];

    // **************************************** relations ****************************************
    public function seenAdmin()
    {
        return $this->belongsTo(User::class, 'seen_admin_id')->withTrashed();
    }

    public function publishedAdmin()
    {
        return $this->belongsTo(User::class, 'published_admin_id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(AdsCategory::class, 'category_id')->withTrashed();
    }

    public function attributes()
    {
        return $this->hasMany(AdsAttribute::class, 'ads_id')->withTrashed();
    }

    public function logoImages()
    {
        return $this->morphMany(Image::class, 'imagable')
            ->where('model_type', 'adsLogo');
    }

    public function contentImages()
    {
        return $this->morphMany(Image::class, 'imagable')
            ->where('model_type', 'adsContent');
    }

    public function country()
    {
        return $this->city->country();
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'ads_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'ads_id');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'bookmarks', 'ads_id', 'user_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->withTrashed();
    }

    public function publishedComments()
    {
        return $this->comments()->published();
    }

    public function views()
    {
        return $this->morphMany(View::class, 'viewable');
    }

	public function appViews()
	{
		return $this->morphToMany(AppView::class, 'app_viewable', '','', 'view_id');
	}


	//    ************************************************** scope section ***********************
    public function scopePublish($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeUnPublished($query)
    {
        return $query->whereNull('published_at');
    }

    public function scopeActive($query)
    {
        return $query
            ->where('start_date', '<', Carbon::now())
            ->where('end_date', '>', Carbon::now());
    }

    //    ************************************************** user functions section ***********************
    public function getNewRecords($count = 5, $select = ['*'], $with = [])
    {
        return $this->select($select)
            ->with($with)
            ->orderBy('created_at', 'desc')
            ->take($count)
            ->get();
    }

    public function getTopLikes($count = 5, $select = ['*'], $with = [])
    {
        return $this->select($select)
            ->with($with)
            ->orderBy('created_at', 'desc')
            ->take($count)
            ->get();
    }

    public function getTopViews($count = 5, $select = ['*'], $with = [])
    {
        return $this->select($select)
            ->with($with)
            ->orderBy('created_at', 'desc')
            ->take($count)
            ->get();
    }

    public function getTopComments($count = 5, $select = ['*'], $with = [])
    {
        return $this->select($select)
            ->with($with)
            ->orderBy('created_at', 'desc')
            ->take($count)
            ->get();
    }

    public function isValid()
    {
        if (
            $this->start_date > Carbon::now() ||
            $this->end_date < Carbon::now()
        ) {
            return false;
        }

        return true;
    }

    // **************************************** getter and setter ****************************************
    public function getLogoAttribute()
    {
        $image = $this->logoImages()
            ->first();

        if (!empty($image)) {

            $path = Storage::disk(config('image.storage.global'))->url('') . 'adsLogo/';
            $existImages['id'] = $image->id;

            foreach (config('image.sizes.logo') as $imageSize => $value) {
                $existImages[$imageSize] = $path . $this->makeImageName($image->title, $image->ext, $value['postfix']);
            }

            return $existImages;
        }

        $path = asset('assets/index/img/content');
	    $noImages['id']=null;
	    foreach (config('image.sizes.logo') as $imageSize => $value) {
		    $noImages[$imageSize] = $path .'/no-image_default'. $value['postfix'].'.png';
	    }

        return $noImages;
    }

    public function getContentImagesAttribute()
    {
        $images = $this->contentImages()->get();

        if (!empty($images->count())) {
            $path = Storage::disk(config('image.storage.global'))->url('') . 'adsContent/';
            $existImages = [];
            foreach ($images as $key => $image) {
                $existImages[$key]['id'] = $image->id;
                foreach (config('image.sizes.image') as $imageSize => $value) {
                    $existImages[$key][$imageSize] = $path . $this->makeImageName($image->title, $image->ext, $value['postfix']);
                }
            }

            return $existImages;
        }


	    $path = asset('assets/index/img/content');
	    $noImages[0]['id']=null;
	    foreach (config('image.sizes.image') as $imageSize => $value) {
		    $noImages[0][$imageSize] = $path .'/no-image_default'. $value['postfix'].'.png';
	    }

	    return $noImages;


    }

    public function getReportCountAttribute()
    {
        return $this->reports()->count() ?? 0;
    }

    public function getScoreAverageAttribute()
    {
        return $this->scores()->average('score') ?? 0;
    }

    public function getBookmarkCountAttribute()
    {
        return $this->bookmarks()->count() ?? 0;
    }

	public function getScoreCountAttribute()
	{
		return $this->scores()->select('score' , DB::raw('COUNT(*) as count'))->groupBy('score')->get() ?? 0;
	}


    public function getPublishedCommentCountAttribute()
    {
        return $this->publishedComments()->count() ?? 0;
    }

    public function getViewCountAttribute()
    {
        return $this->views()->count() ?? 0;
    }

    public function getIsPopularAttribute()
    {
        return $this->like >= 10 ? true : false;
    }

    public function getIsNewAttribute()
    {
        return $this->created_at > Carbon::now()->subDays(3) ? true : false;
    }

    public function setCommentCountAttribute()
    {
        $this->attributes['comment_count'] = $this->comments()->count() ?? 0;
    }

    public function setAverageScoreAttribute($value)
    {
        $sumScore = $this->scores()->sum('score');
        $countScore = $this->scores()->count();
        $this->attributes['average_score'] = !empty($sumScore) ? round($sumScore / $countScore) : 0;
    }



	public function setScoreCountAttribute($score)
	{

		$helper=[];
		$j=0;
		for ( $i = 1; $i <= 5; $i ++ ) {

			$number="score_".$i;

			if ( isset( $score[ $j ]["score"] ) && $score[ $j ]["score"] == $i ) {

				$helper[$number] = intval($score[ $j ]["count"]);
				$j++;
			} else {
				$helper[$number] = 0;
			}
		}
		return $helper;

	}


	public function getShareLink()
    {
        return route('ads.show', ['id' => $this->id, 'title' => $this->urlTitle]);
    }
}
