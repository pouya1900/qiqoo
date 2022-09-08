<?php

namespace App\Http\Requests\Administrator;

use App\Models\AdsCategory;
use Illuminate\Foundation\Http\FormRequest;

class AdsCategoryRequest extends FormRequest
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
        return AdsCategory::$rules;
    }
}
