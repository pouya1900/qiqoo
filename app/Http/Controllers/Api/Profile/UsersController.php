<?php

namespace App\Http\Controllers\Api\Profile;

use App\Exceptions\AppException;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\AdsResource;
use App\Http\Resources\UserResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Traits\ImageUtilsTrait;

class UsersController extends Controller {
	use ImageUtilsTrait;

	protected $request;
	protected $user;
	protected $profile;

	public function __construct( Request $request, User $user, Profile $profile ) {
		$this->request = $request;
		$this->user    = $user;
		$this->profile = $profile;
	}

	public function showProfile() {
		$user = $this->request->user;
		$user->load( 'profile' );

		return $this->sendResponse( new UserResource( $user ), '', config( 'customCodes.response.responseOk' ) );
	}

	public function createProfile() {
		$this->validateRequest( $this->request->all(), [
			'first_name' => 'required|max:50|min:3',
			'last_name'  => 'nullable|max:50|min:3',
			'female'     => 'nullable|boolean',
		] );

		DB::beginTransaction();
		$user = $this->request->user;
		$user->update( $this->request->only( 'first_name', 'last_name' ) );

		if ( empty( $user->profile ) ) {
			$user->profile()->create( [
				'invitation_link' => Str::uuid(),
				'female'          => $this->request->female ?? 0,
			] );
		}

		$user->load( 'profile' );
		DB::commit();

		return $this->sendResponse( new UserResource( $user ), '', config( 'customCodes.response.responseOk' ) );
	}

	public function updateProfile() {
		$this->validateRequest( $this->request->input(), [
			'first_name'        => 'max:50',
			'last_name'         => 'max:50',
			'mobile_visibility' => 'nullable|boolean',
			'email'             => 'nullable|email|min:3|max:50|unique:users,email,' . $this->request->user->id,
			'image'             => 'nullable|integer',
			'female'            => 'nullable|boolean',
			'lat'               => 'nullable|numeric',
			'lon'               => 'nullable|numeric',
			'zip_code'          => 'nullable|max:20',
			'city_id'           => 'nullable|integer',
			'birthday'          => 'nullable|min:3|max:50',
			'address'           => 'nullable|min:3|max:250',
			'company'           => 'nullable|min:3|max:50',
			'en_company'        => 'nullable|min:3|max:50',
			'about'             => 'nullable|min:3|max:50',
			'en_about'          => 'nullable|min:3|max:50',
			'specialist'        => 'nullable|min:3|max:50',
			'en_specialist'     => 'nullable|min:3|max:50',
			'facebook'          => 'nullable|min:3|url|max:50',
			'twitter'           => 'nullable|min:3|url|max:50',
			'linkedin'          => 'nullable|min:3|url|max:50',
			'instagram'         => 'nullable|min:3|url|max:50',
		] );

		DB::beginTransaction();

		$user = $this->request->user;
		$user->update( $this->request->only( [ 'first_name', 'last_name', 'email', 'mobile_visibility' ] ) );

		if ( empty( $user->profile ) ) {
			throw new AppException( trans( 'messages.profile.createRequired' ), config( 'customCodes.response.badRequest' ) );
		}

		if ( ! empty( $this->request->lon ) ) {
			$this->request->merge( [ 'long' => $this->request->lon ] );
		}

		if ( ! empty( $this->request->zip_code ) ) {
			$this->request->merge( [ 'postal_code' => $this->request->zip_code ] );
		}

		if ( ! $this->request->city_id ) {
			unset( $this->request["city_id"] );
		}

		$user->profile->update( $this->request->input() );

		if ( ! empty( $this->request->image ) && $this->request->image != 0 ) {
			$newLogoImage = [ $this->request->image ];
			$oldLogoImage = $user->profileImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
			$this->updateImages( $user, 'userAvatar', $newLogoImage, $oldLogoImage, 'image' );
		} else {

			$image = $user->profileImages()->first();
			if ( ! empty( $image ) ) {
				$this->deleteImages( $image );
				$image->delete();
			}

		}

		DB::commit();

		return $this->sendResponse( new UserResource( $user ), '', config( 'customCodes.response.responseOk' ) );
	}

	public function showAds() {

		$perPage        = $this->getPerPage();
		$isTop          = $this->request->isTop;
		$isImmediate    = $this->request->isImmediate;
		$isVip          = $this->request->isVip;
		$searchString   = $this->request->sc;
		$highestScore   = $this->request->hs;
		$highestComment = $this->request->hc;
		$newest         = $this->request->newest;
		$userAds        = $this->request
			->user
			->ads()
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
			->with( [
				'user',
				'category',
				'city',
				'scores',
				'comments',
				'bookmarks' => function ( $q ) {
					$q->where( 'user_id', '=', $this->request->user->id );
				}
			] )
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


		return $this->sendResponse( [
			'ads'        => $this->sendResponse( AdsResource::collection( $userAds ) ),
			'pagination' => [
				"totalItems"      => $userAds->total(),
				"perPage"         => $userAds->perPage(),
				"nextPageUrl"     => $userAds->nextPageUrl(),
				"previousPageUrl" => $userAds->previousPageUrl(),
				"lastPageUrl"     => $userAds->url( $userAds->lastPage() )
			]
		] );
	}
}
