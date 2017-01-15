@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left">
                    Tunnel Server
                </h3>

                <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addTunnelServerModel">Add Tunnel Server</button>
                <div class="clearfix"></div>

            </div>

            <div class="panel-body">
                <div class="table">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Server Location</th>
                            <th>Server Name</th>
                            <th>Address</th>
                            <th>Added</th>
                            <th>Test</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tunnelServers as $tunnelServer)
                        <tr>
                            <td>{{ $tunnelServer->id }}</td>
                            <td>{{ $tunnelServer->country_code }}</td>
                            <td>{{ $tunnelServer->name }}</td>
                            <td><a href="https://bgpview/ip/{{ $tunnelServer->address }}" target="_blank">{{ $tunnelServer->address }}</a></td>
                            <td>{!! date('j\<\s\u\p\>S\<\/\s\u\p\> F Y', strtotime($tunnelServer->created_at)) !!}</td>
                            <td class="center-td"><a href="#"><span class="glyphicon glyphicon-test"></span></a></td>
                            <td class="center-td"><a href="#"><span class="glyphicon glyphicon-edit"></span></a></td>
                            <td class="center-td"><a href="#"><span class="glyphicon glyphicon-remove"></span></a></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addTunnelServerModel" tabindex="-1" role="dialog" aria-labelledby="addTunnelServerModelLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="addTunnelServerModelLabel">Add new Tunnel Server</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="server-name" class="control-label">Server Name:</label>
                                <input type="text" class="form-control" id="server-name" placeholder="NZ-01-TUNNEL-SERVER" required>
                            </div>
                            <div class="form-group">
                                <label for="server-address" class="control-label">IPv4 Address:</label>
                                <input type="text" class="form-control" id="server-address" placeholder="185.99.132.23" required>
                            </div>
                            <div class="form-group">
                                <label for="server-country-code" class="control-label">Country:</label>
                                <select id="server-country-code" class="form-control">
                                    @foreach (trans('countries') as $countryCode => $countryName)
                                        <option value="{{ $countryCode }}">{{ $countryName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="server-city" class="control-label">Server City Location:</label>
                                <input type="text" class="form-control" id="server-city" placeholder="Auckland" required>
                            </div>
                            <div class="form-group">
                                <label for="server-ssh-password" class="control-label">SSH Root Password:</label>
                                <input type="password" class="form-control" id="server-ssh-password" required>
                            </div>
                            <div class="form-group">
                                <label for="server-ssh-password" class="control-label">Server SSH Port:</label>
                                <input type="text" class="form-control" id="server-ssh-password" value="22" required>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary save-prefix-to-pool">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
