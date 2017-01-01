@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Create New Tunnel</div>

                <div class="panel-body">
                    <form method="POST" action="{{ route('new-tunnel') }}">
                        <h3>Server Location</h3>
                        @foreach($tunnelServers as $tunnelServer)
                            <div class="radio">
                                <label><input type="radio" name="tunnel-server-id" value="{{ $tunnelServer->id }}">{{ $tunnelServer->name }} [ {{ $tunnelServer->address }} ]</label>
                            </div>
                        @endforeach
                        {!! $errors->first('tunnel-server-id', '<span class="error-msg">:message</span>') !!}

                        <hr />

                        <h3>Server Location</h3>
                        <div class="form-group">
                            <label for="remote-ipv4">IPv4 Endpoint (Your IPv4 address):</label>
                            <input type="text" class="form-control" id="remote-ipv4" name="remote-ipv4" required>
                            {!! $errors->first('remote-ipv4', '<span class="error-msg">:message</span>') !!}
                        </div>

                        <hr />

                        <input class="btn btn-primary" type="submit" value="Create Tunnel">

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
