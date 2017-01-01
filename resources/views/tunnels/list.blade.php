@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Your Tunnels</div>
                    {!! dump($tunnels[0]) !!}
                    <div class="panel-body">
                        <div class="table">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Server Name</th>
                                        <th>Server IP</th>
                                        <th>Client IP</th>
                                        <th>Created Date</th>
                                        <th>Server Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tunnels as $tunnel)
                                        <tr>
                                            <td>{{ $tunnel->id }}</td>
                                            <td>{{ $tunnel->server }}</td>
                                            <td>Foo</td>
                                            <td>Foo</td>
                                            <td>Foo</td>
                                            <td>Foo</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
