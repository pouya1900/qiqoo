<?php

namespace App\Http\Requests\Site;

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
        $rules = [
            'text' => 'required|string|max:5000|min:6',
        ];

        if(!auth()->check()) {
            $rules = array_merge([
                'name' => 'required|string|max:100|min:3',
                'mobile' => 'required|digits:10',
                'email' => 'nullable|max:50|email',
            ], $rules);
        }

        return $rules;
    }
}
