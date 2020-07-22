<?php

namespace Samsin33\Passport\Http\Controllers;

use Laminas\Diactoros\Response as Psr7Response;
use Samsin33\Passport\Exceptions\OAuthServerException;
use League\OAuth2\Server\Exception\OAuthServerException as LeagueException;

trait HandlesOAuthErrors
{
    use ConvertsPsrResponses;

    /**
     * Perform the given callback with exception handling.
     *
     * @param  \Closure  $callback
     * @return mixed
     *
     * @throws \Samsin33\Passport\Exceptions\OAuthServerException
     */
    protected function withErrorHandling($callback)
    {
        try {
            return $callback();
        } catch (LeagueException $e) {
            throw new OAuthServerException(
                $e,
                $this->convertResponse($e->generateHttpResponse(new Psr7Response))
            );
        }
    }
}
