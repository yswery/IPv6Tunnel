@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title pull-left">
                    Prefix Pool
                </h3>

                <button class="btn btn-success pull-right add-prefix-to-pool">Add Prefix Resource</button>
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
    </div>
</div>
@endsection
