<?php

namespace App\Http\Controllers\Administrator;

use App\Events\SendPushNotificationEvent;
use App\Http\Requests\Administrator\BlogRequest;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Models\User;
use Carbon\Carbon;
use App\Traits\AdminImageTrait;
use App\Traits\VideoUtilsTrait;
use App\Services\PushNotification\PushNotificationInterface;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Event;

class BlogController extends Controller {
	use AdminImageTrait;
	use VideoUtilsTrait;

	protected $blog;
	protected $category;
	protected $storageDisk;
	private $push_notification;

	public function __construct( Blog $blog, BlogCategory $category, PushNotificationInterface $push_notification ) {
		$this->blog              = $blog;
		$this->category          = $category;
		$this->storageDisk       = config( 'image.storage.global' );
		$this->push_notification = $push_notification;

	}

	public function index() {
		$subSequence = [ 'id' => 0, 'title' => 'همه ی رکوردها' ];
		$blogs       = $this->blog->with( [ 'category', 'admin' ] )->orderByPagination();

		return view( 'v1.admin.pages.blog.index', compact( 'blogs', 'subSequence' ) );
	}

	public function create() {
		
		$categories = $this->category->all();

		$meta = config( 'global.metaData' );

		if ( empty( count( $categories ) ) ) {
			session()->flash( 'notifications', [
				'message'    => 'دسته بندی ها خالی است. لطفا ابتدا دسته بندی ها را بسازید.',
				'alert_type' => 'error'
			] );

			return redirect()->route( 'admin.category.index' );
		}

		return view( 'v1.admin.pages.blog.create', compact( 'categories', 'meta' ) );
	}

	public function show( Blog $blog ) {
		$blog->load( [ 'category', 'publishedComments' ] );

		return view( 'v1.admin.pages.blog.show', compact( 'blog' ) );
	}

	public function store( BlogRequest $request ) {
		try {


			$request->merge( [
				'published_at' => empty( $request->published ) ? null : now(),
				'admin_id'     => auth()->user()->id
			] );

			$blog = $this->blog->create( $request->all() );

			if ( $request->has( 'content_image_id' ) && ! empty( $request->content_image_id ) ) {

					$newLogoImage = $request->content_image_id ;
					$oldLogoImage = $blog->contentImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
					$this->updateImages( $blog, 'blogContent', $newLogoImage, $oldLogoImage, 'image' );
			}


			if ( $request->has( 'content_video_id' ) && ! empty( $request->content_video_id ) ) {

				$newVideo = $request->content_video_id ;
				$oldVideo = $blog->contentImages()->whereNotIn( 'id', $newVideo )->where( 'model_type', 'blogContentVideo' )->get()->pluck( 'id' )->toArray();
				$this->updateVideo( $blog, 'blogContentVideo', $newVideo, $oldVideo );
			}

			session()->flash( 'notifications', [
				'message'    => 'رکورد جدید با موفقیت اضافه شد',
				'alert_type' => 'success'
			] );


//			send push notification
			if ( $blog->punlished_at ) {
				$data = [
					'to'   => "/topics/TOPIC_ALL",
					"data" => [
						"id"       => $blog->id,
						"pushType" => 2
					]
				];
				Event::dispatch( new SendPushNotificationEvent( $data, $this->push_notification ) );
			}

			return redirect()->route( 'admin.blog.show', $blog->id );
		} catch ( \Exception $e ) {
			session()->flash( 'notifications', [
				'message'    => $e->getMessage() . 'خطا در انجام عملیات',
				'alert_type' => 'error'
			] );

			return redirect()->back();
		}
	}

	public function edit( Blog $blog ) {
		$categories = $this->category->all();

		return view( 'v1.admin.pages.blog.edit', compact( 'blog', 'categories' ) );
	}

	public function update( BlogRequest $request, Blog $blog ) {
		try {

			$request->merge( [
				'published_at' => empty( $request->published ) ? null : now(),
				'admin_id'     => auth()->user()->id
			] );

			$blog->update( $request->input() );


			if ( $request->has( 'content_image_id' ) && ! empty( $request->content_image_id ) ) {

				$newLogoImage = $request->content_image_id ;
				$oldLogoImage = $blog->contentImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
				$this->updateImages( $blog, 'blogContent', $newLogoImage, $oldLogoImage, 'image' );
			}


			if ( $request->has( 'content_video_id' ) && ! empty( $request->content_video_id ) ) {

				$newVideo = $request->content_video_id ;
				$oldVideo = $blog->contentImages()->whereNotIn( 'id', $newVideo )->where( 'model_type', 'blogContentVideo' )->get()->pluck( 'id' )->toArray();
				$this->updateVideo( $blog, 'blogContentVideo', $newVideo, $oldVideo );
			}

			session()->flash( 'notifications', [
				'message'    => 'رکورد مورد نظر با موفقیت ویرایش شد',
				'alert_type' => 'success'
			] );

			return redirect()->route( 'admin.blog.show', $blog->id );
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
			if ( empty( $blog = $this->blog->find( $id ) ) ) {
				throw new \Exception( 'داده مورد نظر شما یافت نشد!' );
			}

			$blog->update( [
				'published_at' => empty( $blog->published_at ) ? Carbon::now() : null
			] );

			session()->flash( 'notifications', [
				'message'    => 'رکورد مورد نظر  با موفقیت بروزرسانی شد',
				'alert_type' => 'success'
			] );
			$data = [
				'status'  => 'success',
				'message' => 'رکورد مورد نظر با موفقیت بروزرسانی شد!',
				'data'    => $blog,
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
			if ( empty( $blog = $this->blog->withTrashed()->findOrFail( $id ) ) ) {
				throw new \Exception( 'داده مورد نظر شما یافت نشد!' );
			}

			if ( $blog->trashed() ) {
				$blog->restore();
				$message = 'رکورد مورد نظر با موفقیت بازیابی شد!';
			} else {
				$blog->delete();
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

	public function trashed() {
		$subSequence = [ 'id' => 1, 'title' => 'سطل زباله' ];
		$blogs       = $this->blog->with( [ 'category', 'admin' ] )->onlyTrashed()->OrderByPagination();

		return view( 'v1.admin.pages.blog.index', compact( 'blogs', 'subSequence' ) );
	}

	public function unPublished() {
		$subSequence = [ 'id' => 2, 'title' => 'رکوردهای منتشر نشده' ];
		$blogs       = $this->blog->with( [ 'category', 'admin' ] )->unPublished()->OrderByPagination();

		return view( 'v1.admin.pages.blog.index', compact( 'blogs', 'subSequence' ) );
	}
}
