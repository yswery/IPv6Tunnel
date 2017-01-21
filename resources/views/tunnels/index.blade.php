@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left">
                        Tunnels
                    </h3>

                    <button class="btn btn-success pull-right" data-toggle="modal" data-target="#newTunnelModal">Create a new Tunnel</button>
                    <div class="clearfix"></div>

                </div>                <div class="panel-body">
                    <div class="table">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Server Name</th>
                                    <th>Server IP</th>
                                    <th>Client IP</th>
                                    <th>Server Tunnel IP</th>
                                    <th>Client Tunnel IP</th>
                                    <th>Modified</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tunnels as $tunnel)
                                    <tr>
                                        <td>{{ $tunnel->id }}</td>
                                        <td>{{ $tunnel->server->name }}</td>
                                        <td>{{ $tunnel->local_v4_address }}</td>
                                        <td>{{ $tunnel->remote_v4_address }}</td>
                                        <td>{{ $tunnel->local_tunnel_address }}</td>
                                        <td>{{ $tunnel->remote_tunnel_address }}</td>
                                        <td>{!! date('j\<\s\u\p\>S\<\/\s\u\p\> F Y', strtotime($tunnel->updated_at)) !!}</td>
                                        <td class="center-td"><a href="{{ route('tunnels.details', ['tunnel_id' => $tunnel->id]) }}"><span class="glyphicon glyphicon-edit"></span></a></td>
                                        <td class="center-td"><a href="{{ route('tunnels.delete', ['tunnel_id' => $tunnel->id]) }}"><span class="glyphicon glyphicon-remove"></span></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="newTunnelModal" tabindex="-1" role="dialog" aria-labelledby="newTunnelModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="newTunnelModalLabel">Create a new IPv6 Tunnel</h4>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div id="main-error" class="error-msg"></div>

                                <div class="form-group">
                                    <label for="remote_v4_address" class="control-label">IPv4 Endpoint (Your IPv4 address):</label>
                                    <span class="error-msg pull-right"></span>
                                    <input type="text" class="form-control" id="remote_v4_address" placeholder="102.24.5.42" required>
                                </div>
                                <div class="form-group">
                                    <label for="tunnel_server_id" class="control-label">Tunnel Server:</label>
                                    <span class="error-msg pull-right"></span>
                                    <select id="tunnel_server_id" class="selectpicker form-control">
                                        @foreach ($tunnelServers as $server)
                                            <option value="{{ $server->id }}" data-thumbnail="{{ flag($server->country_code, 24) }}">{{ $server->name }}</option>
                                        @endforeach
                                    </select>
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

        </div>
    </div>
@endsection
