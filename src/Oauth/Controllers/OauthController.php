<?php namespace App\Http\Controllers\Oauth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OauthController extends Controller
{
    /**
     * Get the authorization view
     * for the user to input their credentials
     *
     * @return    mixed
     */
    public function authorizeClient()
    {
        //Get the Auth code parameters
        $authParams = \Authorizer::getAuthCodeRequestParams();

        //Build up the params
        $params = array_except($authParams,'client');

        $params['client_id'] = $authParams['client']->getId();

        $params['scope'] = implode(config('oauth2.scope_delimiter'), array_map(function ($scope) {
            return $scope->getId();
        }, $authParams['scopes']));

        //Build the variables to be passed to the view
        $client = $authParams['client'];

        //Render the authorization form view
        return view('oauth.authorization-form', compact('params', 'client'));
    }

    /**
     * Handles the authorization post call
     *
     * @param  Request $request
     * @return  mixed
     */
    public function postAuthorize(Request $request)
    {
        //Fetch the request parameters
        $params = \Authorizer::getAuthCodeRequestParams();
        $params['user_id'] = \Auth::user()->id;
        $redirectUri = '/';

        // If the user has allowed the client to access its data, redirect back to the client with an auth code.
        if ($request->has('approve')) {
            $redirectUri = \Authorizer::issueAuthCode('user', $params['user_id'], $params);
        }

        // If the user has denied the client to access its data, redirect back to the client with an error message.
        if ($request->has('deny')) {
            $redirectUri = \Authorizer::authCodeRequestDeniedRedirectUri();
        }

        //Return to the set redirection URI
        return redirect($redirectUri);
    }

    /**
     * Issues an access token to the client
     *
     * @return    mixed
     */
    public function issueAccessToken()
    {
        //Issue an access token to the client
        return response()->json(\Authorizer::issueAccessToken());
    }
}