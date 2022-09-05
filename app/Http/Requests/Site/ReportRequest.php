<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'text' => 'required|string|min:3|max:250',
            'report_type_id' => 'required|exists:report_types,id,deleted_at,NULL',
            'name' => 'required|min:3|max:50',
            'mobile' => 'required|digits:10',
            'email' => 'nullable|email|min:3|max:50'
        ];
    }
}
