<?php

namespace App\Http\Requests\Administrator;

use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'title' => 'required|max:250|min:3',
			'en_title' => 'nullable|max:250|min:3',
		];
	}
}
