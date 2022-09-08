<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\Site\AdsStoreRequest;
use App\Http\Requests\Site\AdsUpdateRequest;
use App\Http\Requests\Site\ReportRequest;
use App\Http\Requests\Site\selectCategoryRequest;
use App\Models\Ads;
use App\Models\AdsAttributeDescription;
use App\Models\AdsCategory;
use App\Models\City;
use App\Models\Country;
use App\Models\Report;
use App\Models\ReportType;
use App\Traits\ImageUtilsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdsController extends Controller {
	private $ads;
	private $adsCategory;
	private $city;
	private $reportType;
	private $country;
	private $attributeDescription;
	private $indexPageFields;
	protected $storageDisk;

	use ImageUtilsTrait;

	public function __construct( Ads $ads, AdsCategory $adsCategory, City $city, ReportType $reportType, Country $country, AdsAttributeDescription $attributeDescription ) {
		$this->ads                  = $ads;
		$this->adsCategory          = $adsCategory;
		$this->city                 = $city;
		$this->reportType           = $reportType;
		$this->country              = $country;
		$this->attributeDescription = $attributeDescription;
		$this->indexPageFields      = [
			'id',
			'category_id',
			'city_id',
			'user_id',
			'title',
			'phone',
			'created_at'
		];
		$this->storageDisk          = config( 'image.storage.global' );
	}

	// show all ads by grid view
	public function showGridIndex( Request $request ) {
		$ads = $this->ads
			->select( $this->indexPageFields )
			->publish()
			->active()
			->when( ! empty( $request->st ), function ( $query ) use ( $request ) {
				return $query->where( 'title', 'like', '%' . $request->st . '%' )
				             ->where( 'description', 'like', '%' . $request->st . '%' );
			} )
			->with( [ 'user', 'category', 'city', 'logoImages' ] )
			->orderByPagination( 30 );

		return view( 'v1.site.pages.ads.grid-index', compact( 'ads' ) );
	}

	// show ads detail
	public function show( $id ) {
		$ads = $this->ads->findOrFail( $id );
		if ( Carbon::now() > $ads->end_date ) {
			abort( 404 );
		}

		if ( ( empty( $ads->published_at ) ) && ( ! empty( auth()->user() ) && $ads->user_id != auth()->user()->id ) ) {
			abort( 404 );
		}

		$ads->load( [ 'category', 'attributes', 'user', 'publishedComments', 'comments', 'scores' ] );
		$reportTypes = $this->reportType->select( 'id', 'title' )->get();
		$relatedAds  = $ads->category->getRandomAds();
		$topLikeAds  = $this->ads->getTopLikes( 10 );
		$categories  = $this->adsCategory->paginate( 10 );

		return view( 'v1.site.pages.ads.show', compact( 'ads', 'relatedAds', 'topLikeAds', 'categories', 'reportTypes' ) );
	}

	// search on index page
	public function doSearch( Request $request ) {
		$ads = Ads::query()->with( [ 'user', 'category', 'city' ] )->select();
		if ( ! empty( $request->input( 'category' ) ) ) {
			$ads = $ads->where( 'category_id', $request->input( 'category' ) );
		}
		if ( ! empty( $request->input( 'city' ) ) ) {
			$ads = $ads->where( 'city_id', $request->input( 'city' ) );
		}
		if ( ! empty( $request->input( 'title' ) ) ) {
			$ads = $ads->where( 'title', 'like', '%' . $request->input( 'title' ) . '%' );
		}

		$ads       = $ads->orderBy( 'created_at', 'desc' )->paginate( 30 );
		$ads_style = 'grid';

		return view( 'v1.site.pages.ads.grid-index', compact( 'ads', 'ads_style' ) );
	}

	// show all cities page
	public function getAdsCityIndex() {
		$cities = $this->city->all();

		return view( 'v1.site.pages.ads.city-index', compact( 'cities' ) );
	}

	// show categories that should be shown in category page
	public function getAdsCategoryIndex() {
		$categories = $this->adsCategory->getShowInCategories( 20, [ 'id', 'title' ] );

		return view( 'v1.site.pages.ads.category-index', compact( 'categories' ) );
	}

	public function getAdsIndexAll( $id, $model_type, $title ) {
		$method = 'getAdsIndexBy' . $model_type;

		return $this->$method( $id, $title );
	}

	private function getAdsIndexByCategory( $id, $title ) {
		$category = $this->adsCategory->findOrFail( $id );

		$page['title'] = 'دسته بندی';
		$ads           = $category->ads()->publish()->active()->orderBy( 'created_at', 'desc' )->paginate( 40 );
		$model         = $category;

		return view( 'v1.site.pages.ads.all-index', compact( 'ads', 'page', 'model' ) );
	}

	private function getAdsIndexByCity( $id, $title ) {
		if ( ! $city = $this->city->findOrFail( $id ) ) {
			abort( 404 );
		}

		$page['title'] = 'شهر';
		$ads           = $city->ads()->publish()->active()->orderBy( 'created_at', 'desc' )->paginate( 40 );
		$model         = $city;

		return view( 'v1.site.pages.ads.all-index', compact( 'ads', 'page', 'model' ) );
	}

	public function addScore() {

	}

	public function addBookmark() {

	}

	public function addReport( Ads $ads, ReportRequest $request ) {
		try {
			$request->merge( [
				'ads_id' => $ads->id
			] );
			Report::create( $request->all() );
			$msg = trans( 'messages.report.success' );

			return view( 'v1.site.pages.other.message', compact( 'msg' ) );
		} catch ( \Exception $e ) {
			$msg = trans( 'messages.report.failed' );

			return view( 'v1.site.pages.other.message', compact( 'msg' ) );
		}
	}

	/////////////////////////////////// authenticated user operations
	public function getCategory() {
		$categories = $this->adsCategory->select( 'id', 'title' )->get();

		return view( 'v1.site.pages.ads.select-category', compact( 'categories' ) );
	}

	public function postCategory( selectCategoryRequest $request ) {
		$adsCategory = $this->adsCategory->findOrFail( $request->category_id );

		return redirect()->route( 'ads.new', $adsCategory->id );
	}

	public function create( $id ) {
		$adsCategory = $this->adsCategory->findOrFail( $id );
		$countries   = $this->country->select( 'id', 'title', 'en_title' )
		                             ->wherePhoneCode( env( 'ADS_COUNTRY_CODE', 44 ) )
		                             ->with( 'cities' )
		                             ->get();

		return view( 'v1.site.pages.ads.create', compact( 'countries', 'adsCategory' ) );
	}

	public function store( AdsCategory $adsCategory, AdsStoreRequest $request ) {
		try {
			$inputAttributeDescriptions = $request->input( 'attributes' );
			$attributeDescriptions      = $this->attributeDescription
				->select( 'id', 'field_name', 'ads_attribute_description_value_type_id' )
				->whereIn( 'field_name', array_keys( $inputAttributeDescriptions ) )->get();
			$request->merge( [
				'user_id'          => auth()->user()->id,
				'category_id'      => $adsCategory->id,
				'meta_title'       => $request->title,
				'meta_description' => $request->title,
				'meta_author'      => auth()->user()->fullName,
				'start_date'       => Carbon::now(),
				'end_date'         => Carbon::now()->addDays( config( 'global.ads.expiredDays' ) ),
				'is_free'          => true,
			] );

			DB::beginTransaction();
			$ads = $this->ads->create( $request->all() );

			foreach ( $attributeDescriptions as $attribute ) {
				$ads->attributes()->create( [
					'ads_attribute_description_id' => $attribute->id,
					'integer_value'                => $attribute->adsAttributeDescriptionValueType->title == 'integer' ? $inputAttributeDescriptions[ $attribute->field_name ] : null,
					'string_value'                 => $attribute->adsAttributeDescriptionValueType->title == 'string' ? $inputAttributeDescriptions[ $attribute->field_name ] : null,
				] );
			}

			if ( $request->has( 'logo_image' ) ) {
				$request->merge( [
					'image'         => $request->logo_image,
					'model'         => 'ads',
					'type'          => 'logo',
					'imagable_id'   => $ads->id,
					'imagable_type' => Ads::class
				] );

				$this->storeImage( $request, $this->storageDisk );
			}

			if ( $request->has( 'content_image' ) ) {
				foreach ( $request->content_image as $content_image ) {
					$request->merge( [
						'image'         => $content_image,
						'model'         => 'ads',
						'type'          => 'content',
						'imagable_id'   => $ads->id,
						'imagable_type' => Ads::class
					] );

					$this->storeImage( $request, $this->storageDisk );
				}
			}

			DB::commit();
			session()->flash( 'notifications', [ 'message'    => trans( 'site/messages.adsCreateSuccess' ),
			                                     'alert_type' => 'success'
			] );

			return redirect()->route( 'ads.show', [ $ads->id, $ads->urlTitle ] );
		} catch ( \Exception $e ) {
			DB::rollBack();
			session()->flash( 'notifications', [ 'message'    => trans( 'site/messages.operationFailed' ),
			                                     'alert_type' => 'error'
			] );

			return redirect()->back();
		}
	}

	public function edit( Ads $ads ) {
		if ( empty( $ads->isValid() ) ) {
			abort( 404 );
		}

		$adsCategory = $this->adsCategory->findOrFail( $ads->category_id );
		$countries   = $this->country->select( 'id', 'title', 'en_title' )
		                             ->wherePhoneCode( env( 'ADS_COUNTRY_CODE', 44 ) )
		                             ->with( 'cities' )
		                             ->get();

		return view( 'v1.site.pages.ads.edit', compact( 'ads', 'countries', 'adsCategory' ) );
	}

	public function update( Ads $ads, AdsUpdateRequest $request ) {
		try {
			$inputAttributeDescriptions = $request->input( 'attributes' );
			$attributeDescriptions      = $this->attributeDescription
				->select( 'id', 'field_name', 'ads_attribute_description_value_type_id' )
				->whereIn( 'field_name', array_keys( $inputAttributeDescriptions ) )->get();
			$request->merge( [
				'user_id'          => auth()->user()->id,
				'meta_title'       => $request->title,
				'meta_description' => $request->title,
				'meta_author'      => auth()->user()->fullName,
			] );

			DB::beginTransaction();
			$ads = $ads->update( $request->all() );

			foreach ( $attributeDescriptions as $attribute ) {
				$ads->attributes()->create( [
					'ads_attribute_description_id' => $attribute->id,
					'integer_value'                => $attribute->adsAttributeDescriptionValueType->title == 'integer' ? $inputAttributeDescriptions[ $attribute->field_name ] : null,
					'string_value'                 => $attribute->adsAttributeDescriptionValueType->title == 'string' ? $inputAttributeDescriptions[ $attribute->field_name ] : null,
				] );
			}

			session()->flash( 'notifications', [ 'message'    => trans( 'site/messages.adsUpdateSuccess' ),
			                                     'alert_type' => 'success'
			] );

			return redirect()->route( 'ads.show', [ $ads->id, $ads->urlTitle ] );
		} catch ( \Exception $e ) {
			session()->flash( 'notifications', [ 'message'    => trans( 'site/messages.operationFailed' ),
			                                     'alert_type' => 'error'
			] );

			return redirect()->back();
		}
	}

	public function checkout() {
		return view( 'v1.site.pages.ads-checkout' );
	}
}
