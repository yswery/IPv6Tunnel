@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Your Tunnels</div>
                <div class="panel-body">
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
        </div>
    </div>
@endsection
