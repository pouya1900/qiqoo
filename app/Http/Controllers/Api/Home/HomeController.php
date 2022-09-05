<?php

namespace App\Http\Controllers\Api\Home;

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
use App\Http\Resources\AppViewResource;
use App\Models\Ads;
use App\Models\AdsAttributeDescription;
use App\Models\AdsCategory;
use App\Models\City;
use App\Models\Country;
use App\Models\ReportType;
use App\Models\AppView;
use App\Models\View;
use App\Traits\ImageUtilsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\Logger\ReqLog\RequestLogger;

class HomeController extends Controller {
	protected $ads;
	private $adsCategory;
	private $app_view;
	private $city;
	private $reportType;
	private $country;
	private $attributeDescription;
	protected $request;

	public function __construct( Request $request, Ads $ads, AdsCategory $adsCategory, AppView $app_view, City $city, ReportType $reportType, Country $country, AdsAttributeDescription $attributeDescription ) {
		$this->ads                  = $ads;
		$this->adsCategory          = $adsCategory;
		$this->app_view             = $app_view;
		$this->city                 = $city;
		$this->reportType           = $reportType;
		$this->country              = $country;
		$this->attributeDescription = $attributeDescription;
		$this->request              = $request;
	}

	public function index() {

		$number = $this->request->number;


		$app_view = $this->app_view->query()->publish()->get();

		$app_view->number = $number;


		return $this->sendResponse( [
			'views' => AppViewResource::collection( $app_view )
		] );


	}

	public function show( AppView $app_view ) {

		$perPage = $this->getPerPage();

		if ( $app_view->appViewType->type_content == "ads" ) {
			$ads = $app_view->getAdsPaginate( $perPage );

			return $this->sendResponse( [
				'ads'        => AdsResource::collection( $ads ),
				'pagination' => [
					"totalItems"      => $ads->total(),
					"perPage"         => $ads->perPage(),
					"nextPageUrl"     => $ads->nextPageUrl(),
					"previousPageUrl" => $ads->previousPageUrl(),
					"lastPageUrl"     => $ads->url( $ads->lastPage() )
				]
			] );

		} else {

			$ads_category = $app_view->getAdsCategoriesPaginate( $perPage );

			return $this->sendResponse( [
				'categories' => CategoryResource::collection( $ads_category ),
				'pagination' => [
					"totalItems"      => $ads_category->total(),
					"perPage"         => $ads_category->perPage(),
					"nextPageUrl"     => $ads_category->nextPageUrl(),
					"previousPageUrl" => $ads_category->previousPageUrl(),
					"lastPageUrl"     => $ads_category->url( $ads_category->lastPage() )
				]
			] );

		}


	}


}
