<?php

namespace App\Http\Requests\Api;

use App\Exceptions\AppException;
use App\Models\Support;
use Illuminate\Foundation\Http\FormRequest;

class SupportRequest extends FormRequest
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
        $rules = Support::$rules;
        $rules = array_merge($rules, [
//            'name' => 'required|string|max:50|min:3',
            'mobile' => 'required|digits_between:9,25',
            'email' => 'nullable|max:50|email'
        ]);

        return $rules;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new AppException($validator->errors()->first(), config('responseCode.validationFail'));
    }
}
