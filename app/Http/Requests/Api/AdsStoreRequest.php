<?php

namespace App\Http\Requests\Api;

use App\Exceptions\AppException;
use Illuminate\Foundation\Http\FormRequest;

class AdsStoreRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'title'             => 'required|min:3|max:50',
			'description'       => 'required|min:3|max:2000',
			'category_id'       => 'required|integer|exists:ads_categories,id',
			'attributes'        => 'nullable|array',
			'city_id'           => 'required|integer|exists:cities,id',
			'address'           => 'required|min:5|max:500',
			'lat'               => 'nullable|numeric',
			'long'              => 'nullable|numeric',
			'phone'             => 'required|digits_between:6,15',
			'mobile'            => 'nullable|digits_between:9,25',
			'email'             => 'nullable|email',
//			'website'           => 'nullable|url|min:3|max:50',
//			'facebook'          => 'nullable|url|min:3|max:50',
//			'twitter'           => 'nullable|url|min:3|max:50',
//			'instagram'         => 'nullable|url|min:3|max:50',
//			'youtube'           => 'nullable|url|min:3|max:50',
			'video_link'        => 'nullable|url|min:3|max:200',
			'term'              => 'accepted',
			'logo_id'           => 'nullable|integer|exists:images,id',
			'content_images_id' => 'nullable|array||exists:images,id'
		];
	}

	protected function failedValidation( \Illuminate\Contracts\Validation\Validator $validator ) {
		throw new AppException( $validator->errors()->first(), config( 'responseCode.validationFail' ) );
	}
}
