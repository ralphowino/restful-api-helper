//Oauth 2.0 authorize route
Route::group(['namespace' => 'Oauth'], function () {
    Route::get('oauth/authorizeClient',
    ['as' => 'oauth.authorize.get', 'middleware' => ['check-authorization-params', 'auth'], 'OauthController@authorize']);

    Route::post('oauth/postAuthorize',
    ['as' => 'oauth.authorize.post', 'middleware' => ['csrf', 'check-authorization-params', 'auth'], 'OauthController@postAuthorize']);

    Route::post('oauth/access_token',
    ['as' => 'oauth.issue.accessToken', 'uses' => 'OauthController@issueAccessToken']);
});

//The Client resource routes
Route::group(['middleware' => ['web'], 'namespace' => 'Oauth'], function (){
    Route::get('clients',
        ['as' => 'clients.index', 'uses' => 'ClientsController@index']);

    Route::get('clients/create',
        ['as' => 'clients.create', 'uses' => 'ClientsController@create']);

    Route::get('clients/{client}/show',
        ['as' => 'clients.show', 'uses' => 'ClientsController@show']);

    Route::post('client/store',
        ['as' => 'clients.store', 'uses' => 'ClientsController@store']);

    Route::delete('client/{client}',
        ['as' => 'clients.destroy', 'uses' => 'ClientsController@destroy']);
});