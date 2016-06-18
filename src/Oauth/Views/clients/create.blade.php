@extends('resources.views.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register Application</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('clients.store') }}">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Application Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('homepage_url') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Application URL</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="homepage_url" value="{{ old('homepage_url') }}">
                                    @if ($errors->has('homepage_url'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('homepage_url') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Application Description</label>
                                <div class="col-md-6">
                                    <textarea rows="2"
                                              cols="30"
                                              id="description"
                                              name="description"
                                              class="form-control">
                                        {{ old('app_url') }}
                                    </textarea>
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('app_redirect_url') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Application Redirect URL</label>
                                <div class="col-md-6">
                                    <input type="text"
                                           class="form-control"
                                           name="app_redirect_url"
                                           value="{{ old('app_redirect_url') }}">
                                    @if ($errors->has('app_redirect_url'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('app_redirect_url') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
