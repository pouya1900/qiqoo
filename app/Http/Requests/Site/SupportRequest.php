<?php

namespace App\Http\Requests\Site;

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
        $rules =  Support::$rules;

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
