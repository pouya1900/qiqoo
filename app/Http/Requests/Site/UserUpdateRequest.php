<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'female' => 'required|boolean',
            'birthday' => 'nullable|min:10|max:20',
            'facebook' => 'nullable|url|min:3|max:50',
            'twitter' => 'nullable|url|min:3|max:50',
            'linkedin' => 'nullable|url|min:3|max:50',
            'instagram' => 'nullable|url|min:3|max:50',
            'company' => 'nullable|min:3|max:50',
            'en_company' => 'nullable|min:3|max:50',
            'specialist' => 'nullable|min:3|max:50',
            'en_specialist' => 'nullable|min:3|max:50',
            'about' => 'nullable|min:3|max:2000',
            'en_about' => 'nullable|min:3|max:2000',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ];
    }
}
