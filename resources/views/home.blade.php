@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @foreach($tunnels as $tunnel)
                        <h3>Tunnel Info</h3>
                        Remove Tunnel Server IPv4 Address: <strong>{{ $tunnel->local_v4_address }}</strong><br />
                        Local Tunnel Client IPv4 Address: <strong>{{ $tunnel->remote_v4_address }}</strong><br />
                        <br />
                        Server IPv6 Address: <strong>{{ $tunnel->local_tunnel_address }}</strong><br />
                        Local IPv6 Address: <strong>{{ $tunnel->remote_tunnel_address }}</strong><br />
                        <br />
                        MTU: <strong>{{ $tunnel->mtu_size }}</strong><br />
                        <h3>Routed Prefixes</h3>
                        @foreach($tunnel->prefixes as $prefix)
                            - <strong>{{ $prefix->address . '/' . $prefix->cidr }}</strong><br />
                        @endforeach
                        <br />
                        <hr />
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
