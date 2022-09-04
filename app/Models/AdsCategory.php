<?php

namespace App\Models;

use App\Traits\ImageUtilsTrait;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CustomAttributes;
use App\Traits\CustomActions;
use App\Traits\JalaliDate;
class AdsCategory extends Model
{
    use softDeletes, CustomActions, CustomAttributes, JalaliDate;
	use ImageUtilsTrait;

    protected $table = 'ads_categories';
    protected $guarded = [
        'icon_id', 'logo_id'
    ];
    public static $rules = [
        'title' => 'required|max:250|min:3',
        'en_title' => 'nullable|unique:ads_categories,title|max:250|min:3',
        'description' => 'nullable|max:1000|min:3',
        'en_description' => 'nullable|max:1000|min:3',
        'parent_id' => 'nullable|integer|exists:ads_categories,id',
        'price' => 'nullable|numeric',
        'show_in_home' => 'boolean',
        'show_in_categories' => 'boolean',
        'is_favorite' => 'boolean',
        'order' => 'nullable|numeric',
        'icon' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        'logo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
    ];

    // **************************************** getter and setter ****************************************
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function childs()
    {
        return $this->hasMany(AdsCategory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(AdsCategory::class, 'parent_id');
    }



	public function appViews()
	{
		return $this->morphToMany(AppView::class, 'app_viewable', '','', 'view_id');
	}



    public function logoImages()
    {
        return $this->morphMany(Image::class, 'imagable')
            ->where('model_type', 'adsCategoryLogo');
    }

	public function iconImages()
	{
		return $this->morphMany(Image::class, 'imagable')
		            ->where('model_type', 'adsCategoryIcon');
	}

    public function ads()
    {
        return $this->hasMany(Ads::class, 'category_id');
    }

    public function adsAttributeDescriptions()
    {
        return $this->morphedByMany(AdsAttributeDescription::class, 'categoriable', 'categoriables', 'category_id');
    }

    public function getPayStatusAttribute()
    {
        return $this->is_paid ? $this->price : 'رایگان';
    }

    public function getRandomAds()
    {
        return $this->ads()->inRandomOrder()->get();
    }

    public function getShowInCategories($paginate = 20, $select = ['*'], $with = [])
    {
        return $this->select($select)
            ->with($with)
            ->whereShowInCategories(true)
            ->orderBy('created_at', 'desc')
            ->paginate($paginate);
    }

    public function getLogoAttribute()
    {
        $image = $this->logoImages()
            ->first();

        if (!empty($image)) {

            $path = Storage::disk(config('image.storage.global'))->url('') . 'adsCategoryLogo/';
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

	public function getIconAttribute()
	{
		$image = $this->iconImages()
		              ->first();

		if (!empty($image)) {

			$path = Storage::disk(config('image.storage.global'))->url('') . 'adsCategoryIcon/';
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

	//    ************************************************** scope section ***********************
    public function scopeFavorite($query)
    {
        return $query->whereIsFavorite(true);
    }

    public function scopeHome($query)
    {
        return $query->whereShowInHome(true);
    }
}
