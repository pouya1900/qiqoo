<?php

namespace App\Http\Controllers\Api\Ads;

use App\Http\Requests\Api\AdsReportRequest;
use App\Http\Requests\Api\AdsStoreRequest;
use App\Http\Requests\Api\AdsUpdateRequest;
use App\Http\Requests\Api\CommentRequest;
use App\Http\Requests\Api\ScoreRequest;
use App\Http\Resources\AdsDetailResource;
use App\Http\Resources\AdsResource;
use App\Http\Resources\AdsShortResource;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ReportTypeResource;
use App\Models\Ads;
use App\Models\AdsAttributeDescription;
use App\Models\AdsCategory;
use App\Models\City;
use App\Models\Country;
use App\Models\ReportType;
use App\Traits\ImageUtilsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\Logger\ReqLog\RequestLogger;

class AdsController extends Controller {
	use ImageUtilsTrait;
	protected $ads;
	private $adsCategory;
	private $city;
	private $reportType;
	private $country;
	private $attributeDescription;
	protected $request;

	public function __construct( Request $request, Ads $ads, AdsCategory $adsCategory, City $city, ReportType $reportType, Country $country, AdsAttributeDescription $attributeDescription ) {
		$this->ads                  = $ads;
		$this->adsCategory          = $adsCategory;
		$this->city                 = $city;
		$this->reportType           = $reportType;
		$this->country              = $country;
		$this->attributeDescription = $attributeDescription;
		$this->request              = $request;
	}

	public function index() {

		$perPage        = $this->getPerPage();
		$isTop          = $this->request->isTop;
		$isImmediate    = $this->request->isImmediate;
		$isVip          = $this->request->isVip;
		$searchString   = $this->request->sc;
		$highestScore   = $this->request->hs;
		$highestComment = $this->request->hc;
		$newest         = $this->request->newest;
		$categoryId     = $this->request->category_id["values"];
		$cityId         = $this->request->city_id;
		$short          = $this->request->short;
		$type           = $this->request->type;


		if ( ! empty( $categoryId ) ) {

			$categoryId = json_decode( json_encode( $categoryId ), true );
		}

		$ads = $this->ads->
		query()->
		when( ! empty( $categoryId ), function ( $query ) use ( $categoryId ) {

			for ( $i = 1; $i <= 10; $i ++ ) {
				$child_categoryId = $this->adsCategory->whereIn( 'parent_id', $categoryId )->get()->pluck( 'id' )->toArray();
				$categoryId       = array_merge( $categoryId, $child_categoryId );
			}

			return $query->whereHas( 'category', function ( $q ) use ( $categoryId ) {
				return $q->whereIn( 'id', $categoryId );
			} );
		} )
		                 ->when( ! empty( $cityId ), function ( $query ) use ( $cityId ) {
			                 return $query->whereHas( 'city', function ( $q ) use ( $cityId ) {
				                 return $q->whereId( $cityId );
			                 } );
		                 } )
		                 ->when( ! empty( $isTop ), function ( $query ) use ( $isTop ) {
			                 return $query->whereIsTop( true );
		                 } )
		                 ->when( ! empty( $isImmediate ), function ( $query ) use ( $isImmediate ) {
			                 return $query->whereIsImmediate( true );
		                 } )
		                 ->when( ! empty( $isVip ), function ( $query ) use ( $isVip ) {
			                 return $query->whereIsVip( true );
		                 } )
		                 ->when( ! empty( $searchString ), function ( $query ) use ( $searchString ) {
			                 return $query->where( 'title', 'Like', '%' . $searchString . '%' )
			                              ->where( 'description', 'Like', '%' . $searchString . '%' );
		                 } )
		                 ->when( ! empty( $type ), function ( $query ) use ( $type ) {
			                 return $query->where( 'type', $type );
		                 } )
		                 ->active()
		                 ->publish()
		                 ->when( ! empty( $thisnewest ), function ( $query ) use ( $newest ) {
			                 $query->orderBy( 'updated_at', 'desc' )
			                       ->orderBy( 'created_at', 'desc' );
		                 } )
		                 ->when( ! empty( $highestScore ), function ( $query ) use ( $highestScore ) {
			                 return $query->orderBy( 'average_score', 'desc' );
		                 } )
		                 ->when( ! empty( $highestComment ), function ( $query ) use ( $highestComment ) {
			                 return $query->orderBy( 'comment_count', 'desc' );
		                 } )
		                 ->when( ! empty( $newest ), function ( $query ) use ( $newest ) {
			                 $query->orderBy( 'updated_at', 'desc' )
			                       ->orderBy( 'created_at', 'desc' );
		                 } )
		                 ->paginate( $perPage );

		$ads = $this->checkBookmarkStatus( $ads, $this->request->user );

		return $this->sendResponse( [
			'ads'        => ! empty( $short ) && $short ? AdsShortResource::collection( $ads ) : AdsResource::collection( $ads ),
			'pagination' => [
				"totalItems"      => $ads->total(),
				"perPage"         => $ads->perPage(),
				"nextPageUrl"     => $ads->nextPageUrl(),
				"previousPageUrl" => $ads->previousPageUrl(),
				"lastPageUrl"     => $ads->url( $ads->lastPage() )
			]
		] );
	}

	public function show( Ads $ads ) {
		$ads                        = $this->checkBookmarkStatus( $ads, $this->request->user );
		$ads->publishedComments     = $ads->publishedComments()
		                                  ->orderBy( 'published_at', 'desc' )
		                                  ->take( 10 )
		                                  ->get();
		$ads->publishedCommentCount = $ads->getPublishedCommentCountAttribute();
		$score                      = $ads->getScoreCountAttribute();

		$ads->Scores = $ads->setScoreCountAttribute( $score );

		$ads->viewCount = $ads->getViewCountAttribute();


		$this->addView( $ads );

		return $this->sendResponse( new AdsDetailResource( $ads ) );
	}

	public function store( AdsStoreRequest $request ) {
		
		$user = $this->request->user;
		$request->merge( [
			'user_id'          => $this->request->user->id,
			'postal_code'      => $request->zip_code,
			'meta_title'       => $request->title,
			'meta_description' => $request->title,
			'meta_author'      => $user->fullName,
			'start_date'       => Carbon::now(),
			'end_date'         => Carbon::now()->addDays( config( 'global.ads.expiredDays' ) ),
			'is_free'          => true,
			'long'             => $request->lon,

		] );

		DB::beginTransaction();

		$ads = $this->ads->create( $request->all() );

		$newLogoImage = [ $request->logo_id ];
		$oldLogoImage = $this->ads->logoImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
		$this->updateImages( $ads, 'adsLogo', $newLogoImage, $oldLogoImage, 'logo' );

		$newContentImages = $request->content_images_id ?? [];
		$oldContentImages = $this->ads->contentImages()->whereNotIn( 'id', $newContentImages )->get()->pluck( 'id' )->toArray();
		$this->updateImages( $ads, 'adsContent', $newContentImages, $oldContentImages, 'image' );

		if ( ! empty( $request->attributes ) ) {
			$inputAttributeDescriptions = $request->input( 'attributes' );
			$attributeDescriptions      = $this->attributeDescription
				->select( 'id', 'field_name', 'ads_attribute_description_value_type_id' )
				->whereIn( 'field_name', array_keys( $inputAttributeDescriptions ) )->get();

			foreach ( $attributeDescriptions as $attribute ) {
				$ads->attributes()->create( [
					'ads_attribute_description_id' => $attribute->id,
					'integer_value'                => $attribute->adsAttributeDescriptionValueType->title == 'integer' ? $inputAttributeDescriptions[ $attribute->field_name ] : null,
					'string_value'                 => $attribute->adsAttributeDescriptionValueType->title == 'string' ? $inputAttributeDescriptions[ $attribute->field_name ] : null,
				] );
			}
		}
		DB::commit();

		return $this->sendResponse( new AdsDetailResource( $ads ), trans( 'api/messages.ads.createSuccess' ) );
	}

	public function update( AdsUpdateRequest $request, Ads $ads ) {
		$user = $this->request->user;
		$request->merge( [
			'postal_code'      => $request->zip_code,
			'user_id'          => $user->id,
			'meta_title'       => $request->title,
			'meta_description' => $request->title,
			'meta_author'      => $user->fullName,
			'is_free'          => true,
		] );

		$ads->update( $request->all() );

		if ( ! empty( $request->logo_id ) ) {
			$newLogoImage = [ $request->logo_id ];
			$oldLogoImage = $ads->logoImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
			$this->updateImages( $ads, 'adsLogo', $newLogoImage, $oldLogoImage, 'logo' );
		}

		if ( ! empty( $request->content_images_id ) ) {
			$newContentImages = $request->content_images_id;
			$oldContentImages = $ads->contentImages()->whereNotIn( 'id', $newContentImages )->get()->pluck( 'id' )->toArray();
			$this->updateImages( $ads, 'adsContent', $newContentImages, $oldContentImages, 'image' );
		}

		$ads = $this->ads->find( $ads->id );

		return $this->sendResponse( new AdsDetailResource( $ads ), trans( 'api/messages.ads.updateSuccess' ) );
	}

	public function getAttributesByCategory( AdsCategory $adsCategory ) {
		$attributes = $adsCategory
			->adsAttributeDescriptions()
			->with( 'adsAttributeDescriptionValueType' )
			->get();

		return $this->sendResponse( AttributeResource::collection( $attributes ) );
	}

	public function getReportTypes() {
		$reportTypes = $this->reportType->all();

		return $this->sendResponse( ReportTypeResource::collection( $reportTypes ) );
	}

	public function addComment( CommentRequest $request, Ads $ads ) {
		$ads->comments()->create( $request->all() );

		return $this->sendResponse();
	}

	public function addReport( AdsReportRequest $request, Ads $ads ) {


		$ads->reports()->create( $request->all() );

		return $this->sendResponse();
	}

	public function addScore( ScoreRequest $request, Ads $ads ) {
		$user = $this->request->user;
		$ads->scores()->updateOrCreate( [ 'user_id' => $user->id ], [
			'user_id' => $user->id,
			'score'   => $request->score
		] );

		return $this->sendResponse();
	}

	public function addView( Ads $ads ) {
		$user = $this->request->user;

		$user_login = $user->getUserLogin();


		$ads->views()->updateOrCreate( [ 'user_id' => $user->id ], [
			'user_id'  => $user->id,
			'uuid'     => $user_login->uuid,
			'platform' => $user_login->platform,
			'model'    => $user_login->model,
			'os'       => $user_login->os,
		] );

		return $this->sendResponse();
	}

	public function addBookmark( Ads $ads ) {
		if ( $ads->bookmarks->contains( $this->request->user ) ) {
			return $this->sendError( trans( 'api/messages.bookmark.duplication' ), config( 'responseCode.validationFail' ) );
		}

		$ads->bookmarks()->attach( $this->request->user, [ 'created_at' => Carbon::now() ] );

		return $this->sendResponse();
	}

	public function removeBookmark( Ads $ads ) {
		if ( empty( $ads->bookmarks->contains( $this->request->user ) ) ) {
			return $this->sendError( trans( 'api/messages.bookmark.notFound' ), config( 'responseCode.validationFail' ) );
		}

		$ads->bookmarks()->detach( $this->request->user );

		return $this->sendResponse();
	}

	public function showBookmarks() {
		$perPage = $this->getPerPage();
		$userAds = $this->request->user->bookmarks()->paginate( $perPage );
		$userAds = $this->checkBookmarkStatus( $userAds, $this->request->user );

		return $this->sendResponse( [
			'bookmarks'  => AdsResource::collection( $userAds ),
			'pagination' => [
				"totalItems"      => $userAds->total(),
				"perPage"         => $userAds->perPage(),
				"nextPageUrl"     => $userAds->nextPageUrl(),
				"previousPageUrl" => $userAds->previousPageUrl(),
				"lastPageUrl"     => $userAds->url( $userAds->lastPage() )
			]
		] );
	}

	public function getCategories() {
		$perPage    = $this->getPerPage();
		$showInHome = $this->request->in_home;

		$categories = $this->adsCategory->
		query()
		                                ->when( ! empty( $showInHome ), function ( $query ) use ( $showInHome ) {
			                                return $query->whereShowInHome( true );
		                                } )
		                                ->orderBy( 'order' )
		                                ->paginate( $perPage );


		return $this->sendResponse( [
			'categories' => CategoryResource::collection( $categories ),
			'pagination' => [
				"totalItems"      => $categories->total(),
				"perPage"         => $categories->perPage(),
				"nextPageUrl"     => $categories->nextPageUrl(),
				"previousPageUrl" => $categories->previousPageUrl(),
				"lastPageUrl"     => $categories->url( $categories->lastPage() )
			]
		] );


	}
}
