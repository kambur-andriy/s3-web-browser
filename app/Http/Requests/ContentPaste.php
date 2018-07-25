<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class ContentPaste extends FormRequest
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
	        'source_path_list' => 'required|array|bail',
	        'destination_path' => 'required|string|bail',
	        'operation' => 'required|string|bail',
        ];
    }
}
