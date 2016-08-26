<?php

namespace Ralphowino\ApiStarter\Resources;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

trait JWTAuthenticationTrait
{
    /**
     * Login the user
     *
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        // Authenticate user by the user's credentials
        $token = $this->authenticate($request->only('email', 'password'));

        // Authentication passed and return the token
        return $this->response()->array(compact('token'))->setStatusCode(200);
    }

    /**
     * Register a new user
     * 
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request) {
        //Validate the user credentials
        $validator = $this->validator($request->all());

        //Check if validation passed
        if ($validator->fails()) {
            $this->returnValidationErrors($validator);
        }

        //Create new user
        $user = $this->create($request->all());

        //Authenticate new user
        $token = $this->authenticate($request->only('email', 'password'));
        $message = 'User successfully created';

        return $this->response()->array(compact('user', 'token', 'message'))->setStatusCode(201);
    }

    /**
     * Authenticate the user by their credentials
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Array $data)
    {
        try {
            // Attempt to verify the credentials and create a token for the user
            if (! $token = \JWTAuth::attempt($data)) {
                return $this->response()->array(['error' => 'invalid_credentials'])->setStatusCode(401);
            }
        } catch (JWTException $e) {
            // Something went wrong whilst attempting to encode the token
            return $this->response()->array(['error' => 'could_not_create_token'])->setStatusCode(500);
        }

        // Authentication passed and return the token
        return $token;
    }
}