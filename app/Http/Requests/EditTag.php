<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class EditTag extends FormRequest
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
        	'id' => 'required|integer|bail',
	        'name' => 'required|string|bail',
	        'category' => 'required|integer|min:1|bail',
	        'parent_tag' => 'required|integer|bail',
        ];
    }

	/**
	 * Custom validation messages
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'category.min' => 'The category field is required.',
		];
	}

}
