@extends('resources.views.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a href="{{ route('clients.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i>  Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2>{{ $client->name }}</h2>
                    </div>

                    <div class="panel-body">
                        <li><strong>Client Id:</strong> {{$client->id}}</li>
                        <li><strong>Client Secret:</strong> {{$client->secret}}</li>
                        <li><strong>App Redirect Url:</strong> {{$client->redirect_uri->redirect_uri}}</li>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
