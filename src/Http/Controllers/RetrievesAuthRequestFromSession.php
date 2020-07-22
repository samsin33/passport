<?php

namespace Samsin33\Passport\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Samsin33\Passport\Bridge\User;
use Samsin33\Passport\Exceptions\InvalidAuthTokenException;

trait RetrievesAuthRequestFromSession
{
    /**
     * Make sure the auth token matches the one in the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Samsin33\Passport\Exceptions\InvalidAuthTokenException
     */
    protected function assertValidAuthToken(Request $request)
    {
        if ($request->has('auth_token') && $request->session()->get('authToken') !== $request->get('auth_token')) {
            $request->session()->forget(['authToken', 'authRequest']);

            throw InvalidAuthTokenException::different();
        }
    }

    /**
     * Get the authorization request from the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \League\OAuth2\Server\RequestTypes\AuthorizationRequest
     *
     * @throws \Exception
     */
    protected function getAuthRequestFromSession(Request $request)
    {
        return tap($request->session()->get('authRequest'), function ($authRequest) use ($request) {
            if (! $authRequest) {
                throw new Exception('Authorization request was not present in the session.');
            }

            $authRequest->setUser(new User($request->user()->getAuthIdentifier()));

            $authRequest->setAuthorizationApproved(true);
        });
    }
}
