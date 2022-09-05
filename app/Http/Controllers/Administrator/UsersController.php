<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests\Administrator\UserCreateRequest;
use App\Http\Requests\Administrator\UserUpdateRequest;
use App\Traits\AdminImageTrait;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\ImageUploader\ImageUploader;

use App\Models\Role;
use App\Models\User;
use App\Models\Profile;

class UsersController extends Controller {
	use AdminImageTrait;

	protected $user;
	protected $role;
	protected $profile;
	protected $storageDisk;

	public function __construct( Role $role, User $user, Profile $profile ) {
		$this->user    = $user;
		$this->role    = $role;
		$this->profile = $profile;
		$this->storageDisk = config( 'image.storage.global' );

	}

	public function getAllUsers( $role = 'all' ) {
		$subSequence = [ 'id' => 0, 'title' => 'مدیریت کاربران' ];
		$users       = '';
		switch ( $role ) {
			case 'all' :
				$users                = $this->user->getAllUsers()->orderByPagination();
				$subSequence['title'] = 'مدیریت همه کاربران';
				break;

			case 'standardUser' :
				$users                = $this->user->getStandardUsers()->orderByPagination();
				$subSequence['title'] = 'مدیریت کاربران عضو';
				break;

			case 'adminUser' :
				$users                = $this->user->getAdminUsers()->where( 'id', '<>', auth()->user()->id )->orderByPagination();
				$subSequence['title'] = 'مدیریت مدیرها';
				break;
			default:
				abort( 404 );
				break;
		}

		return view( 'v1.admin.pages.user.index', compact( 'subSequence', 'users' ) );
	}

	public function show( User $user ) {
		$roles = $this->role->query()->whereNotIn( 'name', [ 'superAdmin' ] )->get();

		$user = $this->user->with( [ 'profile' ] )->find( $user->id );

		return view( 'v1.admin.pages.user.show', compact( 'user', 'roles' ) );
	}

	public function showProfile() {
		$user  = auth()->user()->load( [ 'profile' ] );
		$roles = [];

		return view( 'v1.admin.pages.user.show', compact( 'user', 'roles' ) );
	}

	public function create() {
		$roles = $this->role->query()->whereNotIn( 'name', [ 'standardUser', 'superAdmin' ] )->get();

		if ( empty( $roles->count() ) ) {
			session()->flash( 'notifications', [ 'message'    => 'دسترسی ها خالی است. لطفا ابتدا دسترسی ها را بسازید.',
			                                     'alert_type' => 'error'
			] );

			return redirect()->route( 'admin.role.index' );
		}

		return view( 'v1.admin.pages.user.create', compact( 'roles' ) );
	}

	public function store( UserCreateRequest $request ) {
		try {
			DB::beginTransaction();

			$request->merge( [
				'is_active'       => true,
				'activated_at'    => Carbon::now(),
				'invitation_link' => Str::uuid(),
				'role_id'         => $request->role,
				'password'        => bcrypt( $request->password )
			] );

			$user = $this->user->create( $request->all() );

			if ( $request->has( 'avatar_id' ) && ! empty( $request->avatar_id ) ) {

				$newLogoImage = $request->avatar_id ;
				$oldLogoImage = $user->profileImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
				$this->updateImages( $user, 'userAvatar', $newLogoImage, $oldLogoImage, 'image' );
			}

			$user->profile()->create( $request->all() );

			session()->flash( 'notifications', [ 'message'    => trans( 'messages.crud.createdModelSuccess' ),
			                                     'alert_type' => 'success'
			] );

			DB::commit();

			return redirect()->route( 'admin.user.all' );
		} catch ( \Exception $e ) {
			DB::rollBack();
			session()->flash( 'notifications', [ 'message'    => trans( 'messages.crud.createdModelFail' ),
			                                     'alert_type' => 'error'
			] );

			return redirect()->back();
		}
	}

	//update image in saveImage trait
	public function update( UserUpdateRequest $request, User $user ) {


		try {
			DB::beginTransaction();

			$oldImage = '';
			if ( ! empty( $user->profile->image ) ) {
				$oldImage = $user->profile->image;
			}
			$request->merge( [
				'role_id'  => $request->role ?? $user->role_id,
				'password' => ! empty( $request->password ) ? bcrypt( $request->password ) : $user->password
			] );
			$user->update( $request->all() );
			$profile = $this->profile->where( 'user_id', $user->id )->first();

			if ( $request->has( 'avatar_id' ) && ! empty( $request->avatar_id ) ) {

				$newLogoImage = $request->avatar_id ;
				$oldLogoImage = $user->profileImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
				$this->updateImages( $user, 'userAvatar', $newLogoImage, $oldLogoImage, 'image' );
			}

			$profile->update( $request->all() );

			DB::commit();

			session()->flash( 'notifications', [ 'message'    => trans( 'messages.crud.updatedModelSuccess' ),
			                                     'alert_type' => 'success'
			] );

			return redirect()->route( 'admin.user.show', $user->id );
		} catch ( \Exception $e ) {
			DB::rollBack();
			session()->flash( 'notifications', [ 'message'    => trans( 'messages.crud.updatedModelFail' ),
			                                     'alert_type' => 'error'
			] );

			return redirect()->back();
		}
	}

	/* ajax type functions */
	public function doDelete( User $user ) {
		try {
			DB::beginTransaction();
			$user->delete();
			session()->flash( 'notifications', [ 'message'    => trans( 'messages.crud.deletedModelSuccess' ),
			                                     'alert_type' => 'success'
			] );
			$data = [
				'status'  => 'success',
				'message' => trans( 'messages.crud.deletedModelSuccess' ),
				'data'    => [],
				'code'    => 200
			];

			return response()->json( $data, 200, [], JSON_UNESCAPED_UNICODE );
		} catch ( \Exception $e ) {
			$data = [
				'status'  => 'fail',
				'message' => trans( 'messages.crud.deletedModelFail' ),
				'data'    => [],
				'code'    => 400
			];

			return response()->json( $data, 200, [], JSON_UNESCAPED_UNICODE );
		}
	}

	public function trash( $id ) {
		try {
			$user = $this->user->withTrashed()->find( $id );
			if ( $user->trashed() ) {
				$user->restore();
				$message = trans( 'messages.crud.unTrashedModelSuccess' );
			} else {
				$user->delete();
				$message = trans( 'messages.crud.trashedModelSuccess' );
			}
			session()->flash( 'notifications', [ 'message' => $message, 'alert_type' => 'success' ] );
			$data = [
				'status'  => 'success',
				'message' => $message,
				'data'    => [],
				'code'    => 200
			];

			return response()->json( $data, 200, [], JSON_UNESCAPED_UNICODE );
		} catch ( \Exception $e ) {
			$data = [
				'status'  => 'fail',
				'message' => trans( 'messages.crud.deletedModelFail' ),
				'data'    => [],
				'code'    => 400
			];

			return response()->json( $data, 200, [], JSON_UNESCAPED_UNICODE );
		}
	}

	public function trashed( $role = 'all' ) {
		$subSequence = [ 'id' => 1, 'title' => 'بن شده ها' ];
		$users       = '';
		switch ( $role ) {
			case 'all' :
				$users                = $this->user->onlyTrashed()->paginate( 100 );
				$subSequence['title'] = 'کاربران بن شده';
				break;

			case 'standardUser' :
				$users                = $this->user->getStandardUsers()->onlyTrashed()->paginate( 100 );
				$subSequence['title'] = 'کاربران بن شده';
				break;

			case 'adminUser' :
				$users                = $this->user->getAdminUsers()->onlyTrashed()->paginate( 100 );
				$subSequence['title'] = 'مدیران بن شده';
				break;
			default:
				abort( 404 );
				break;
		}

		return view( 'v1.admin.pages.user.index', compact( 'subSequence', 'users' ) );
	}

}
