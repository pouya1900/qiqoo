<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests\Administrator\AppViewRequest;
use App\Models\AppView;
use App\Models\AppViewType;
use App\Models\AdsCategory;
use App\Models\Ads;
use App\Models\User;
use Carbon\Carbon;
use App\Traits\AdminImageTrait;
use Illuminate\Support\Facades\Event;

class AppViewController extends Controller {
	use AdminImageTrait;

	protected $app_view;
	protected $app_view_type;
	protected $ads;
	protected $category;
	protected $storageDisk;

	public function __construct( AppView $app_view, AppViewType $app_view_type, Ads $ads, AdsCategory $category ) {
		$this->app_view      = $app_view;
		$this->app_view_type = $app_view_type;
		$this->ads           = $ads;
		$this->category      = $category;
		$this->storageDisk   = config( 'image.storage.global' );

	}

	public function index() {
		$subSequence = [ 'id' => 0, 'title' => 'همه ی رکوردها' ];
		$view        = $this->app_view->with( [ 'ads', 'adsCategories' ] )->orderByPagination();


		return view( 'v1.admin.pages.app-view.index', compact( 'view', 'subSequence' ) );
	}

	public function create() {

		$types    = $this->app_view_type->get();
		$ads      = $this->ads->query()->publish()->get();
		$categories = $this->category->get();

		$meta = config( 'global.metaData' );

		if ( empty( count( $types ) ) ) {
			session()->flash( 'notifications', [
				'message'    => 'نوع ویو خالی است. لطفا ابتدا انواع را بسازید.',
				'alert_type' => 'error'
			] );

//			return redirect()->route( 'admin.category.index' );
		}

		return view( 'v1.admin.pages.app-view.create', compact( 'types', 'ads', 'categories', 'meta' ) );
	}

	public function show( AppView $app_view ) {

		$app_view->load( [ 'appViewType','ads','adsCategories' ] );

		return view( 'v1.admin.pages.app-view.show', compact( 'app_view' ) );
	}

	public function store( AppViewRequest $request ) {

		try {


			$request->merge( [
				'published_at' => empty( $request->published ) ? null : now(),
			] );

			$app_view = $this->app_view->create( $request->all() );

			if ($request->has('ads')) {

				$ads_id=$request->ads;
				$ads=$this->ads->find($ads_id);

				foreach ($ads as $row) {
					$app_view->ads()->save($row);
				}

			}

			if ($request->has('categories')) {

				$categories_id=$request->categories;
				$categories=$this->category->find($categories_id);

				foreach ($categories as $row) {
					$app_view->adsCategories()->save($row);
				}

			}

			if ( $request->has( 'background_image_id' ) && ! empty( $request->background_image_id ) ) {

				$newLogoImage = $request->background_image_id;
				$oldLogoImage = $app_view->backgroundImage()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
				$this->updateImages( $app_view, 'homeBackgroundImage', $newLogoImage, $oldLogoImage, 'image' );
			}


			session()->flash( 'notifications', [
				'message'    => 'رکورد جدید با موفقیت اضافه شد',
				'alert_type' => 'success'
			] );


//

			return redirect()->route( 'admin.app-view.show', $app_view->id );
		} catch ( \Exception $e ) {
			session()->flash( 'notifications', [
				'message'    => $e->getMessage() . 'خطا در انجام عملیات',
				'alert_type' => 'error'
			] );

			return redirect()->back();
		}
	}

	public function edit( AppView $app_view ) {

		$types    = $this->app_view_type->get();
		$ads      = $this->ads->query()->publish()->get();
		$categories = $this->category->get();
		$app_view->load( [ 'appViewType','ads','adsCategories' ] );

		$ads_id      = $app_view->ads->pluck( 'id' )->toArray();
		$categories_id = $app_view->adsCategories->pluck( 'id' )->toArray();

		return view( 'v1.admin.pages.app-view.edit', compact( 'app_view', 'types' , 'ads' , 'ads_id' , 'categories' , 'categories_id' ) );
	}

	public function update( AppViewRequest $request, AppView $app_view ) {
		try {

			$request->merge( [
				'published_at' => empty( $request->published ) ? null : now(),
			] );


			$app_view->update( $request->all() );

			$app_view->ads()->detach();
			$app_view->adsCategories()->detach();


			if ($request->has('ads')) {

				$ads_id=$request->ads;
				$ads=$this->ads->find($ads_id);

				foreach ($ads as $row) {
					$app_view->ads()->save($row);
				}

			}

			if ($request->has('categories')) {

				$categories_id=$request->categories;
				$categories=$this->category->find($categories_id);

				foreach ($categories as $row) {
					$app_view->adsCategories()->save($row);
				}

			}

			if ( $request->has( 'background_image_id' ) && ! empty( $request->background_image_id ) ) {

				$newLogoImage = $request->background_image_id;
				$oldLogoImage = $app_view->backgroundImage()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
				$this->updateImages( $app_view, 'homeBackgroundImage', $newLogoImage, $oldLogoImage, 'image' );
			}


			session()->flash( 'notifications', [
				'message'    => 'رکورد مورد نظر با موفقیت ویرایش شد',
				'alert_type' => 'success'
			] );

			return redirect()->route( 'admin.app-view.show', $app_view->id );
		} catch ( \Exception $e ) {
			session()->flash( 'notifications', [
				'message'    => $e->getMessage() . 'خطا در انجام عملیات',
				'alert_type' => 'error'
			] );

			return redirect()->back();
		}
	}
	/* ajax type functions */
//    public function doDelete($id)
//    {
//        try {
//
//            if($blog = $this->blog->findOrFail($id)){
//                throw new Exception('داده مورد نظر شما یافت نشد!');
//            }
//
//            if (!empty($blog->comments()->count())) {
//                $blog->comments()->delete();
//            }
//
//            if (!empty($blog->reports()->count())) {
//                $blog->reports()->delete();
//            }
//
//            $blog->forceDelete();
//
//            $destination_path = storage_path('/assets/blogs/');
//            $uploader = new Uploader($destination_path, ['png', 'jpg', 'jpeg'], 2);
//            $prefix_name = [
//                '',
//                'logo_small_',
//                'logo_medium_',
//                'logo_large_',
//            ];
//            // delete from table and storage
//            if(count($blog->logo()))
//            {
//                foreach ($prefix_name as $row){
//                    $uploader->deleteFile($destination_path . $row . $blog->logo()->path);
//                }
//                $blog->logo()->delete();
//            }
//
//            session()->flash('notifications', ['message' => 'عملیات با موفقیت انجام شد', 'alert_type' => 'success']);
//            $data = [
//                'status' => 'success',
//                'message' => 'عملیات با موفقیت انجام شد',
//                'data' => ['alireza'],
//                'code' => 200
//            ];
//            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
//        } catch (\Exception $e) {
//            session()->flash('notifications', ['message' => 'خطا در انجام عملیات: لطفا با مدیر سایت تماس بگیرید.', 'alert_type' => 'warning']);
//            $data = [
//                'status' => 'fault',
//                'message' => 'خطا: ' . $e->getMessage(),
//                'data' => [],
//                'code' => 400
//            ];
//            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
//        }
//    }

	public function publish( $id ) {
		try {
			if ( empty( $app_view = $this->app_view->find( $id ) ) ) {
				throw new \Exception( 'داده مورد نظر شما یافت نشد!' );
			}

			$app_view->update( [
				'published_at' => empty( $app_view->published_at ) ? Carbon::now() : null
			] );

			session()->flash( 'notifications', [
				'message'    => 'رکورد مورد نظر  با موفقیت بروزرسانی شد',
				'alert_type' => 'success'
			] );
			$data = [
				'status'  => 'success',
				'message' => 'رکورد مورد نظر با موفقیت بروزرسانی شد!',
				'data'    => $app_view,
				'code'    => 200
			];

			return response()->json( $data, 200, [], JSON_UNESCAPED_UNICODE );
		} catch ( \Exception $e ) {
			session()->flash( 'notifications', [
				'message'    => 'خطا در انجام عملیات: لطفا با مدیر سایت تماس بگیرید.',
				'alert_type' => 'warning'
			] );
			$data = [
				'status'  => 'fault',
				'message' => 'خطا: ' . $e->getMessage(),
				'data'    => [],
				'code'    => 400
			];

			return response()->json( $data, 200, [], JSON_UNESCAPED_UNICODE );
		}
	}

	public function trash( $id ) {
		try {
			if ( empty( $app_view = $this->app_view->withTrashed()->findOrFail( $id ) ) ) {
				throw new \Exception( 'داده مورد نظر شما یافت نشد!' );
			}

			if ( $app_view->trashed() ) {
				$app_view->restore();
				$message = 'رکورد مورد نظر با موفقیت بازیابی شد!';
			} else {
				$app_view->delete();
				$message = 'رکورد مورد نظر با موفقیت به سطل زباله منتقل شد!';
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
			session()->flash( 'notifications', [
				'message'    => 'خطا در انجام عملیات: لطفا با مدیر سایت تماس بگیرید.',
				'alert_type' => 'warning'
			] );
			$data = [
				'status'  => 'fault',
				'message' => 'خطا: ' . $e->getMessage(),
				'data'    => [],
				'code'    => 400
			];

			return response()->json( $data, 200, [], JSON_UNESCAPED_UNICODE );
		}
	}

//	public function trashed() {
//		$subSequence = [ 'id' => 1, 'title' => 'سطل زباله' ];
//		$app_view       = $this->app_view->with( [ 'category', 'admin' ] )->onlyTrashed()->OrderByPagination();
//
//		return view( 'v1.admin.pages.blog.index', compact( 'blogs', 'subSequence' ) );
//	}

//	public function unPublished() {
//		$subSequence = [ 'id' => 2, 'title' => 'رکوردهای منتشر نشده' ];
//		$blogs       = $this->blog->with( [ 'category', 'admin' ] )->unPublished()->OrderByPagination();
//
//		return view( 'v1.admin.pages.blog.index', compact( 'blogs', 'subSequence' ) );
//	}
}
