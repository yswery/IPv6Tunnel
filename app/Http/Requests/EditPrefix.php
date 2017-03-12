<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditPrefix extends FormRequest
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
            'prefix_id' => 'required|integer',
            'name'      => 'required|string|max:100',
        ];
    }
}
