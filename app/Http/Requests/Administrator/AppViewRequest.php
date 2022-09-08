<?php

namespace App\Http\Requests\Administrator;

use App\Models\AppView;
use Illuminate\Foundation\Http\FormRequest;

class AppViewRequest extends FormRequest
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
		return AppView::$rules;
	}
}
