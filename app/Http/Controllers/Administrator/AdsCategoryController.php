<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Requests\Administrator\AdsCategoryRequest;
use App\Models\AdsCategory;
use App\Traits\AdminImageTrait;

class AdsCategoryController extends Controller {
	use AdminImageTrait;

	protected $category;
	protected $storageDisk;

	public function __construct( AdsCategory $category ) {
		$this->category    = $category;
		$this->storageDisk = config( 'image.storage.global' );
	}

	public function index() {
		$allCategories = $this->category->orderByPagination();

		return view( 'v1.admin.pages.ads-category.index', compact( 'allCategories' ) );
	}

	public function create() {
		$allCategories = $this->category->all();

		return view( 'v1.admin.pages.ads-category.create', compact( 'allCategories' ) );
	}

	public function store( AdsCategoryRequest $request ) {

		try {
            $this->validate($request, [
                'title' => 'unique:ads_categories,title'
            ]);

			$request->request->add( [ 'admin_id' => auth()->user()->id ] );

			$category = $this->category->create( $request->all() );


			if ( $request->has( 'logo_id' ) && ! empty( $request->logo_id ) ) {

				$newLogoImage = $request->logo_id ;
				$oldLogoImage = $category->logoImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
				$this->updateImages( $category, 'adsCategoryLogo', $newLogoImage, $oldLogoImage, 'logo' );
			}


			if ( $request->has( 'icon_id' ) && ! empty( $request->icon_id ) ) {

				$newLogoImage = $request->icon_id ;
				$oldLogoImage = $category->iconImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
				$this->updateImages( $category, 'adsCategoryIcon', $newLogoImage, $oldLogoImage, 'logo' );
			}


			session()->flash( 'notifications', [
				'message'    => 'عملیات با موفقیت انجام شد',
				'alert_type' => 'success'
			] );

			return redirect()->route( 'admin.category.index' );
		} catch ( \Exception $e ) {
			session()->flash( 'notifications', [
				'message'    => $e->getMessage() . 'خطا در انجام عملیات',
				'alert_type' => 'error'
			] );

			return redirect()->back();
		}
	}

	public function edit( $id ) {
		$category      = $this->category->findOrFail( $id );
		$allCategories = $this->category->whereNotIn( 'id', [ $category->id ] )->get();

		return view( 'v1.admin.pages.ads-category.edit', compact( 'category', 'allCategories' ) );
	}

	public function update( AdsCategoryRequest $request, $id ) {
		try {
			$category = $this->category->findOrFail( $id );

			$this->validate( $request, [
				'title'     => 'unique:ads_categories,title,' . $category->id,
				'parent_id' => 'not_in:' . $category->id
			] );

			$request->merge( [
				'admin_id' => auth()->user()->id
			] );

			$category->update( $request->input() );


			if ( $request->has( 'logo_id' ) && ! empty( $request->logo_id ) ) {

				$newLogoImage = $request->logo_id ;
				$oldLogoImage = $category->logoImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
				$this->updateImages( $category, 'adsCategoryLogo', $newLogoImage, $oldLogoImage, 'logo' );
			}


			if ( $request->has( 'icon_id' ) && ! empty( $request->icon_id ) ) {

				$newLogoImage = $request->icon_id ;
				$oldLogoImage = $category->iconImages()->whereNotIn( 'id', $newLogoImage )->get()->pluck( 'id' )->toArray();
				$this->updateImages( $category, 'adsCategoryIcon', $newLogoImage, $oldLogoImage, 'logo' );
			}

			session()->flash( 'notifications', [
				'message'    => 'عملیات با موفقیت انجام شد',
				'alert_type' => 'success'
			] );

			return redirect()->route( 'admin.category.index' );
		} catch ( \Exception $e ) {
			session()->flash( 'notifications', [
				'message'    => $e->getMessage() . 'خطا در انجام عملیات',
				'alert_type' => 'error'
			] );

			return redirect()->back();
		}
	}

	public function doDelete( AdsCategory $category ) {
		try {
			$category->load( [ 'ads', 'childs' ] );

			if ( ! empty( $category->ads()->count() ) ) {
				throw new \Exception( 'آگهی با چنین دسته بندی وجود دارد. لطفا ابتدا دسته بندی آگهی مورد نظر را تغییر دهید.' );
			}

			if ( $category->childs()->count() ) {
				throw new \Exception( 'این دسته بندی دارای زیر دسته می باشد. لطفا ابتدا والد زیردسته مورد نظر را تغییر دهید.' );
			}

			$category->delete();

			$data = [
				'status'  => 'success',
				'message' => 'عملیات با موفقیت انجام شد',
				'data'    => [ 'record deleted' ],
				'code'    => 200
			];
			session()->flash( 'notifications', [ 'message' => $data['message'], 'alert_type' => 'success' ] );

			return response()->json( $data, 200, [], JSON_UNESCAPED_UNICODE );
		} catch ( \Exception $e ) {
			session()->flash( 'notifications', [
				'message'    => 'خطا در انجام عملیات: ' . $e->getMessage(),
				'alert_type' => 'error'
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
}
