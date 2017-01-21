<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrefixPool extends FormRequest
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
            'tunnel_server_id' => 'required|integer|exists:tunnel_servers,id',
            'address'          => 'required|ip|unique:prefix_pool,address',
            'cidr'             => 'required|integer',
        ];
    }
}
