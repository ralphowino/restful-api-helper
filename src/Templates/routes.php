$api = app(Dingo\Api\Routing\Router::class);

$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers'], function ($api) {
        //API routes
        $api->group(['namespace' => 'Api'], function ($api) {
            //Authentication routes
            $api->group(['namespace' => 'Auth'], function ($api) {
                $api->post('/auth/login', 'AuthController@login');
                $api->post('/auth/register', 'AuthController@register');
            });
        });
    });
});