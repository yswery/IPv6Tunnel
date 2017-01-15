<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTunnelServers extends FormRequest
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
            'address'      => 'required|string|ip|unique:tunnel_servers,address',
            'name'         => 'required|string|unique:tunnel_servers,name',
            'city'         => 'required|string',
            'country_code' => 'required|string|min:2|max:2',
            'ssh_port'     => 'required|integer',
            'ssh_password' => 'required|string',

        ];
    }
}
