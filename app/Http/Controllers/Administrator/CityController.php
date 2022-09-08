<?php

namespace App\Http\Controllers\Administrator;

use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Carbon\Carbon;
use App\Traits\AdminImageTrait;
use App\Http\Requests\Administrator\CityRequest;

class CityController extends Controller {
	use AdminImageTrait;

	protected $city;
	protected $country;
	protected $storageDisk;

	public function __construct( City $city , Country $country ) {
		$this->city        = $city;
		$this->country        = $country;
		$this->storageDisk = config( 'image.storage.global' );

	}

	public function index() {
		$subSequence = [ 'id' => 0, 'title' => 'همه ی رکوردها' ];
		$cities      = $this->city->paginate(20);

		return view( 'v1.admin.pages.city.index', compact( 'cities', 'subSequence' ) );
	}

	public function create() {

		$countries=$this->country->all();

		return view( 'v1.admin.pages.city.create' , compact('countries') );
	}

	public function store( CityRequest $request ) {
		try {

			$city = $this->city->create( $request->all() );

			if ( $request->has( 'logo_id' ) && ! empty( $request->logo_id ) ) {

				$newLogoImage = [ $request->logo_id ];
				$oldLogoImage = $city->logoImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
				$this->updateImages( $city, 'cityLogo', $newLogoImage, $oldLogoImage, 'logo' );
			}

			session()->flash( 'notifications', [
				'message'    => 'رکورد جدید با موفقیت اضافه شد',
				'alert_type' => 'success'
			] );


			return redirect()->route( 'admin.city.show', $city->id );
		} catch ( \Exception $e ) {
			session()->flash( 'notifications', [
				'message'    => $e->getMessage() . 'خطا در انجام عملیات',
				'alert_type' => 'error'
			] );

			return redirect()->back();
		}
	}

	public function edit( City $city ) {

		$countries=$this->country->all();


		return view( 'v1.admin.pages.city.edit', compact( 'city' , 'countries' ) );
	}

	public function update( CityRequest $request, City $city ) {
		try {

			$city->update( $request->input() );


			if ( $request->has( 'logo_id' ) && ! empty( $request->logo_id ) ) {

				$newLogoImage = [$request->logo_id];
				$oldLogoImage = $city->logoImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
				$this->updateImages( $city, 'cityLogo', $newLogoImage, $oldLogoImage, 'logo' );
			}



			session()->flash( 'notifications', [
				'message'    => 'رکورد مورد نظر با موفقیت ویرایش شد',
				'alert_type' => 'success'
			] );

			return redirect()->route( 'admin.city.index');
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


}
