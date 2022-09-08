<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Traits\ImageUtilsTrait;
use App\Traits\CustomAttributes;
use App\Traits\CustomActions;
use App\Traits\JalaliDate;
class City extends Model
{
	use ImageUtilsTrait;

	protected $guarded = ['logo_id'];


	public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function ads()
    {
        return $this->hasMany(Ads::class);
    }


	public function logoImages()
	{
		return $this->morphMany(Image::class, 'imagable')
		            ->where('model_type', 'cityLogo');
	}

	public function getLogoAttribute()
	{
		$image = $this->logoImages()
		              ->first();

		if (!empty($image)) {

			$path = Storage::disk(config('image.storage.global'))->url('') . 'cityLogo/';
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
