<?php

namespace Samsin33\Passport\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Samsin33\Passport\Exceptions\MissingScopeException;

class CheckClientCredentials extends CheckCredentials
{
    /**
     * Validate token credentials.
     *
     * @param  \Samsin33\Passport\Token  $token
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function validateCredentials($token)
    {
        if (! $token) {
            throw new AuthenticationException;
        }
    }

    /**
     * Validate token credentials.
     *
     * @param  \Samsin33\Passport\Token  $token
     * @param  array  $scopes
     * @return void
     *
     * @throws \Samsin33\Passport\Exceptions\MissingScopeException
     */
    protected function validateScopes($token, $scopes)
    {
        if (in_array('*', $token->scopes)) {
            return;
        }

        foreach ($scopes as $scope) {
            if ($token->cant($scope)) {
                throw new MissingScopeException($scope);
            }
        }
    }
}
