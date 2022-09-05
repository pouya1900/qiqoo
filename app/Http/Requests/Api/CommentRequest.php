<?php

namespace App\Http\Requests\Api;

use App\Exceptions\AppException;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'text' => 'required|string|max:5000|min:2',
            'name' => 'required|string|max:50|min:3',
            'mobile' => 'required|digits_between:9,25',
            'email' => 'nullable|max:50|email'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new AppException($validator->errors()->first(), config('responseCode.validationFail'));
    }
}
