@extends('resources.views.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2>Developer Applications</h2>
                    </div>

                    <div class="panel-body">
                        <div class="pull-right">
                            <a href="{{ route('clients.create')  }}" class="btn btn-default">
                                Register Application
                            </a>
                        </div>
                        <div class="row">
                            <ol>
                                @foreach( $clients as $client)
                                    <li>
                                        <a href="{{ url('/clients/' . $client->id . '/show') }}">
                                            {{ $client->name }}
                                        </a>
                                    <span>
                                        <b>Client id:</b> {{ $client->id }}
                                    </span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-md-offset-3 text-center">
                {!! $clients->render() !!}
            </div>
        </div>
    </div>
@endsection