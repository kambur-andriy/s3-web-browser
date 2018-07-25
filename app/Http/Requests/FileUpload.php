<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class FileUpload extends FormRequest
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
        	'files_list' => 'required|array|bail',
	        'path' => 'required|string|bail',
        ];
    }
}
