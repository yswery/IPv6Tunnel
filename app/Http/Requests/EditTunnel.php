<?php

namespace App\Http\Requests;

use App\Models\Tunnel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EditTunnel extends FormRequest
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
        $tunnel = Tunnel::find($request->tunnel_id);

        return [
            'mtu_size' => 'required|integer|min:1280|max:1480',
            'remote_v4_address' => [
                'required',
                'ip',
                Rule::unique('tunnels')->where(function ($query) use ($tunnel) {
                    $query->where('id', '!=', $tunnel->id)->where('tunnel_server_id', $tunnel->server->id);
                }),
            ],
        ];
    }
}
