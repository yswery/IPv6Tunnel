@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Your Tunnels</div>
                <div class="panel-body">
                    <h3>Tunnel Details</h3>
                    <ul>
                        <li>Server Name: <strong>{{ $tunnel->server->name }}</strong></li>
                        <li>Server Address: <strong>{{ $tunnel->local_v4_address }}</strong></li>
                        <li>Client Address: <strong>{{ $tunnel->remote_v4_address }}</strong> EDIT BUTTON</li>
                        <li>Server Tunnel Address: <strong>{{ $tunnel->local_tunnel_address }}</strong></li>
                        <li>Client Tunnel Address: <strong>{{ $tunnel->remote_tunnel_address }}</strong></li>
                        <li>MTU Size: <strong>{{ $tunnel->mtu_size }}</strong> EDIT BUTTON</li>
                    </ul>

                    <h3>Tunneled Prefixes</h3>
                    <ul>
                        @foreach($tunnel->routed_prefixes as $prefix)
                            <li>
                                <strong>{{ $prefix->address }}/{{ $prefix->cidr }}</strong>
                                <span class="pointer glyphicon glyphicon-edit" data-toggle="modal" data-target="#{{ $prefix->id }}-prefix-edit-modal"></span>
                            </li>
                            <!-- Modal -->
                            <div class="modal fade" id="{{ $prefix->id }}-prefix-edit-modal" tabindex="-1" role="dialog" aria-labelledby="{{ $prefix->id }}-prefix-lable">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="{{ $prefix->id }}-prefix-lable">{{ $prefix->address }}/{{ $prefix->cidr }}</h4>
                                        </div>
                                        <div class="modal-body">
                                            ...
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <form action="{{ route('tunnels.add-prefix', ['tunnel_id' => $tunnel->id]) }}" method="post">
                            <input type="submit" class="btn btn-info" value="Add Prefix">
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
