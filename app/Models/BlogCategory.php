<?php

namespace App\Models;

use App\Traits\ImageUtilsTrait;

use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CustomAttributes;
use App\Traits\CustomActions;
use App\Traits\JalaliDate;

class BlogCategory extends Model
{
    use softDeletes, CustomActions, CustomAttributes, JalaliDate;
	use ImageUtilsTrait;

    protected $table = 'categories';
    protected $guarded = [
	    'icon_id'
    ];

    public static $rules = [
        'title' => 'required|max:250|min:3',
        'en_title' => 'nullable|unique:categories,title|max:250|min:3',
        'parent_id' => 'nullable|integer|exists:categories,id',
        'description' => 'nullable|max:1000|min:3',
        'en_description' => 'nullable|max:1000|min:3',
        'color_code' => 'nullable|max:10|min:1',
        'is_favorite' => 'required|boolean',
        'order' => 'nullable|integer'
    ];

    public function blogs()
    {
        return $this->hasMany( Blog::class, 'category_id');
    }

    public function ads()
    {
        return $this->hasMany(Ads::class, 'category_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function childs()
    {
        return $this->hasMany(BlogCategory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    public function getFavoriteStatusAttribute()
    {
        return $this->is_favorite ? 'بله' : 'خیر';
    }

    public function iconImages()
    {
        return $this->morphMany(Image::class, 'imagable')
            ->where('model_type', 'blogCategory');
    }

    public function getIconAttribute()
    {
        $image = $this->iconImages()
            ->first();

        if (!empty($image)) {

            $path = Storage::disk(config('image.storage.global'))->url('') . 'blogCategory/';
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
}
