@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left">
                    Prefix Pool
                </h3>

                <button class="btn btn-success pull-right" data-toggle="modal" data-target="#addPrefixPoolModel">Add Prefix Resource</button>
                <div class="clearfix"></div>

            </div>

            <div class="panel-body">
                <div class="table">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Prefix</th>
                            <th>Server Location</th>
                            <th>Server Name</th>
                            <th>Sub Prefixes</th>
                            <th>Modified</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($prefixPool as $prefix)
                        <tr>
                            <td>{{ $prefix->id }}</td>
                            <td>{{ $prefix->prefix }}</td>
                            <td>{{ $prefix->server->country_code }}</td>
                            <td>{{ $prefix->server->name }}</td>
                            <td>{{ $prefix->subPrefixes()->count() }}</td>
                            <td>{!! date('j\<\s\u\p\>S\<\/\s\u\p\> F Y', strtotime($prefix->updated_at)) !!}</td>
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
        <div class="modal fade" id="addPrefixPoolModel" tabindex="-1" role="dialog" aria-labelledby="addPrefixPoolModelLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="addPrefixPoolModelLabel">Add new Prefix to Pool</h4>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="prefix-id" class="control-label">Prefix IP:</label>
                                <input type="text" class="form-control" id="prefix-id" placeholder="2a06:1280::">
                            </div>
                            <div class="form-group">
                                <label for="prefix-id" class="control-label">Prefix CIDR:</label>
                                <input type="text" class="form-control" id="prefix-cidr" placeholder="32">
                            </div>
                            <div class="form-group">
                                <label for="prefix-server" class="control-label">Server:</label>
                                <select id="prefix-server" class="form-control">
                                    @foreach ($tunnelServers as $server)
                                        <option data-id="{{ $server->id }}">{{ $server->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
