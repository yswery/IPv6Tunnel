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

                    <button class="btn btn-success" data-toggle="modal" data-target="#editTunnelModal">Edit Tunnel Settings</button>

                    <!-- Modal -->
                    <div class="modal fade" id="editTunnelModal" tabindex="-1" role="dialog" aria-labelledby="editTunnelModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="editTunnelModalLabel">Edit Tunnel</h4>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div id="main-error" class="error-msg"></div>

                                        <div class="form-group">
                                            <label for="remote_v4_address" class="control-label">IPv4 Endpoint (Your IPv4 address):</label>
                                            <span class="error-msg pull-right"></span>
                                            <input type="text" class="form-control" id="remote_v4_address" value="{{ $tunnel->remote_v4_address }}" placeholder="102.24.5.42" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="remote_v4_address" class="control-label">MTU Size:</label>
                                            <span class="error-msg pull-right"></span>
                                            <input type="text" class="form-control" id="mtu_size" value="{{ $tunnel->mtu_size  }}" placeholder="1450" required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary save-modal-data">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>


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
                                            <form>
                                                <div id="{{ $prefix->id }}-prefix-error" class="main-error error-msg"></div>

                                                <div class="form-group">
                                                    <label for="prefix_name" class="control-label">Prefix Name (Whois Record):</label>
                                                    <span class="error-msg pull-right"></span>
                                                    <input type="text" class="form-control" id="{{ $prefix->id }}-prefix-name" value="{{ $prefix->name }}" placeholder="Bob's Routed IPv6 block" required>
                                                </div>

                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary save-modal-data" data-id="{{ $prefix->id }}">Save changes</button>
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
