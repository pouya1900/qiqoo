<?php

namespace App\Models;

use App\Traits\ImageUtilsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CustomAttributes;
use App\Traits\CustomActions;
use App\Traits\JalaliDate;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    use softDeletes, CustomActions, CustomAttributes, JalaliDate;
    use ImageUtilsTrait;
    //    ************************************************** attribute section ***********************
    protected $guarded = ['published' , 'content_video_id' , 'content_image_id' , 'video_manual_duration'];
    public static $rules = [
        'title' => 'required|max:250|min:3',
        'en_title' => 'nullable|max:250|min:3',
        'short_description' => 'required|max:2000|min:10',
        'en_short_description' => 'nullable|max:2000|min:10',
        'text' => 'required|max:36350|min:10',
        'en_text' => 'nullable|max:36350|min:10',
        'category_id' => 'required|integer|exists:categories,id',
        'published' => 'required|boolean',
        'meta_title' => 'required|max:500|min:3',
        'meta_author' => 'required|max:100|min:3',
        'meta_keywords' => 'required|max:250|min:3',
        'meta_description' => 'required|max:1000|min:3',
        'logo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
    ];

    //    ************************************************** relation section ***********************
    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function contentImages()
    {
        return $this->morphMany(Image::class, 'imagable')
            ->where('model_type', 'blogContent');
    }

	public function contentVideo()
	{
		return $this->morphMany(Image::class, 'imagable')
		            ->where('model_type', 'blogContentVideo');
	}

	public function logo()
	{
		return $this->morphMany(Image::class, 'imagable')
		            ->where('model_type', 'blogContent');
	}

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function publishedComments()
    {
        return $this->comments()
            ->published();
    }

    public function views()
    {
        return $this->morphMany(View::class, 'viewable');
    }

    //    ************************************************** scope section ***********************
    public function scopeScAdmin($query, $admin)
    {
        return $query->where('admin_id', $admin);
    }

    public function scopePublish($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeUnPublished($query)
    {
        return $query->whereNull('published_at');
    }

    // **************************************** getter and setter ****************************************
    public function getContentImagesAttribute()
    {
        $images = $this->contentImages()->get();

        if(!empty($images->count())){
            $path = Storage::disk(config('image.storage.global'))->url('') . 'blogContent/';
            $existImages = [];
            foreach ($images as $key => $image) {
                $existImages[$key]['id'] = $image->id;
                foreach(config('image.sizes.image') as $imageSize => $value){
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


	public function getContentVideoAttribute()
	{
		$images = $this->contentVideo()->get();

		if(!empty($images->count())){
			$path = Storage::disk(config('image.storage.global'))->url('') . 'blogContentVideo/';
			$existVideo = [];
//			foreach ($images as $key => $image) {
				$existVideo['id'] = $images[0]->id;
				$existVideo['link'] = $path . $images[0]->title;
				$existVideo['duration'] = $images[0]->duration;
//			}

			return $existVideo;
		}

		$path = asset('assets/index/img/content');
		return [

				'id'=>0,
				'link' => $path . '/no-image_default_tiny.png',
				'duration'=>0

		];
	}


	public function getLogoAttribute()
    {
        $image = $this->contentImages()
            ->first();

        if (!empty($image)) {

            $path = Storage::disk(config('image.storage.global'))->url('') . 'blogContent/';
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

    public function getShareLink()
    {
        return route('blog.show', ['blog' => $this->id, 'title' => $this->urlTitle]);
    }
}
