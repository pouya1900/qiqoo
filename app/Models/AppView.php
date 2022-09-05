<?php

namespace App\Models;


use App\Traits\ImageUtilsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CustomAttributes;
use App\Traits\CustomActions;
use App\Traits\JalaliDate;
use Illuminate\Support\Facades\Storage;

class AppView extends Model {
	use softDeletes, CustomActions, CustomAttributes, JalaliDate;
	use ImageUtilsTrait;


	protected $guarded = [ 'published', 'background_image_id' , 'ads' , 'categories' ];

	public static $rules = [
//		'title'       => 'required|string|min:3|max:250',
//		'description' => 'required|string|max:2000|min:6',
//		'type'        => 'required|integer|exists:app_views,id',
//		'published'   => 'required|boolean',
//		'need_space'  => 'required|boolean',
//		'action'      => 'required|string|min:3|max:250',

	];


	public function adsCategories() {
		return $this->morphedByMany( AdsCategory::class, 'app_viewable', 'app_viewables', 'view_id' );

	}


	public function ads() {

		return $this->morphedByMany( Ads::class, 'app_viewable', 'app_viewables', 'view_id' );


	}


	public function appViewType() {
		return $this->belongsTo( AppViewType::class, 'type' );
	}


	public function backgroundImage() {
		return $this->morphMany( Image::class, 'imagable' )
		            ->where( 'model_type', 'homeBackgroundImage' );
	}


	public function getView( $type ) {

		return $this->select()->where( 'type', $type )->where( 'is_show', 1 )->get();

	}


	public function getBackgroundImageAttribute() {
		$image = $this->backgroundImage()
		              ->first();

		if ( ! empty( $image ) ) {

			$path              = Storage::disk( config( 'image.storage.global' ) )->url( '' ) . 'homeBackgroundImage/';
			$existImages['id'] = $image->id;

			foreach ( config( 'image.sizes.image' ) as $imageSize => $value ) {
				$existImages[ $imageSize ] = $path . $this->makeImageName( $image->title, $image->ext, $value['postfix'] );
			}

			return $existImages;
		}

		$path           = asset( 'assets/index/img/content' );
		$noImages['id'] = null;
		foreach ( config( 'image.sizes.image' ) as $imageSize => $value ) {
			$noImages[ $imageSize ] = $path . '/no-image_default' . $value['postfix'] . '.png';
		}

		return $noImages;
	}


	public function getAdsCategoriesPaginate( $number ) {

		return $this->adsCategories()->paginate( $number );

	}

	public function getAdsPaginate( $number ) {

		return $this->ads()->paginate( $number );

	}

	//    ************************************************** scope section ***********************

	public function scopePublish( $query ) {
		return $query->whereNotNull( 'published_at' );
	}

	public function scopeUnPublished( $query ) {
		return $query->whereNull( 'published_at' );
	}
	


}
