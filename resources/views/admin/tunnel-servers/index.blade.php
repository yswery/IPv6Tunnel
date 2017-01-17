@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left">
                    Tunnel Servers
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
                            <th>SSH Test</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tunnelServers as $tunnelServer)
                        <tr>
                            <td>{{ $tunnelServer->id }}</td>
                            <td><img src="{{ flag($tunnelServer->country_code, 24) }}" /> {{ $tunnelServer->city }}, {{ countryName($tunnelServer->country_code) }}</td>
                            <td>{{ $tunnelServer->name }}</td>
                            <td><a href="https://bgpview.io/ip/{{ $tunnelServer->address }}" target="_blank">{{ $tunnelServer->address }}</a></td>
                            <td>{!! date('j\<\s\u\p\>S\<\/\s\u\p\> F Y', strtotime($tunnelServer->created_at)) !!}</td>
                            <td class="center-td"><a href="#"><span data-server-id="{{ $tunnelServer->id }}" class="glyphicon glyphicon-refresh test-ssh"></span></a></td>
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
                            <div id="main-error" class="error-msg"></div>

                            <div class="form-group">
                                <label for="name" class="control-label">Server Name:</label>
                                <span class="error-msg pull-right"></span>
                                <input type="text" class="form-control" id="name" placeholder="NZ-01-TUNNEL-SERVER" required>
                            </div>
                            <div class="form-group">
                                <label for="address" class="control-label">IPv4 Address:</label>
                                <span class="error-msg pull-right"></span>
                                <input type="text" class="form-control" id="address" placeholder="185.99.132.23" required>
                            </div>
                            <div class="form-group">
                                <label for="country_code" class="control-label">Country:</label>
                                <span class="error-msg pull-right"></span>
                                <select id="country_code" class="form-control">
                                    @foreach (trans('countries') as $countryCode => $countryName)
                                        <option value="{{ $countryCode }}">{{ $countryName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city" class="control-label">Server City Location:</label>
                                <span class="error-msg pull-right"></span>
                                <input type="text" class="form-control" id="city" placeholder="Auckland" required>
                            </div>
                            <div class="form-group">
                                <label for="ssh_password" class="control-label">SSH Root Password:</label>
                                <span class="error-msg pull-right"></span>
                                <input type="password" class="form-control" id="ssh_password" required>
                            </div>
                            <div class="form-group">
                                <label for="ssh_port" class="control-label">Server SSH Port:</label>
                                <span class="error-msg pull-right"></span>
                                <input type="text" class="form-control" id="ssh_port" value="22" required>
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
