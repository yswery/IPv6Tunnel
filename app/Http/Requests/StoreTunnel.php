<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreTunnel extends FormRequest
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
    public function rules(Request $request)
    {
        $serverId = $request->get('tunnel_server_id');

        return [
            'tunnel_server_id' => 'required|integer|exists:tunnel_servers,id',
            'remote_v4_address' => [
                'required',
                'ip',
                Rule::unique('tunnels')->where(function ($query) use ($serverId) {
                    $query->where('tunnel_server_id', $serverId);
                }),
            ],
        ];
    }
}
