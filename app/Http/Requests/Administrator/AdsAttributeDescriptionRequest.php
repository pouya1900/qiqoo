<?php

namespace App\Http\Requests\Administrator;

use App\Models\AdsAttributeDescription;
use Illuminate\Foundation\Http\FormRequest;

class AdsAttributeDescriptionRequest extends FormRequest
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
        return AdsAttributeDescription::$rules;
    }
}
