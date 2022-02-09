<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

/**
 * @property function attemptLogin
 * @property function sendFailedLoginResponse
 *  @property function successLoginResponse
 * @property function notRegisteredResponse
 *  @property function UserNotFoundResponse
 */

trait AuthTrait
{
    /**
     * Attempt to log the user into the application.
     * @param
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function attemptLogin(Object $user, Object  $request)
    {
        return (Hash::check($request->password, $user->password)) ? true : false;
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return response()->json([
            "status" => false,
            "message" => "Please check your login details and try again."
        ], 405);
    }

    protected function successLoginResponse($user_data)
    {
        return response()->json([
            'status' => true,
            'message' => 'Login Successful',
            'data' => $user_data['data'],
            'access_token' => $user_data['access_token'],
            'token_type' => $user_data['token_type']
        ], 200);
    }
    protected function notRegisteredResponse()
    {
        return response()->json([
            'status' => false,
            'message' => 'Not yet registered on this application',
            'error' => 'User is valid but doesnt have access to this application'
        ], 401);
    }
    protected function errorResponse($e)
    {
        return response()->json([
            'status' => false,
            'error' => $e->getMessage()
        ], 400);
    }
    protected function UserNotFoundResponse(Request $request)
    {
        return response()->json([
            'status' => false,
            'message' => "$request->login_info not found"
        ], 404);
    }

    protected function InvalidTokenResponse()
    {
        return response()->json([
            'status' => false,
            'message' => 'Please login',
            'error' => 'Invalid api token'
        ], 401);
    }

    public function createdSuccessfullResponse($newCreation = null)
    {
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $newCreation
        ], 200);
    }

    public function failedtocreateResponse()
    {
        return response()->json([
            'status' => false,
            'message' => 'Failed to create',
        ], 400);
    }
}
