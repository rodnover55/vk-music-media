<?php

namespace VkMusic\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use VkMusic\Models\Token;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ApiAuthMiddleware
{
    /** @var  Guard */
    private $auth;

    private $except = [
        'api/token'
    ];

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next) {
        if (
            $this->shouldPassThrough($request) ||
            $this->validate($request)
        ) {
            return $next($request);
        }

        return new Response('Token is not found', Response::HTTP_UNAUTHORIZED);
    }

    protected function shouldPassThrough($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }

    protected function validate(Request $request) {
        $token = $request->header('X-Token');

        if (empty($token)) {
            return false;
        }

        /** @var Token $auth */
        $auth = Token::with('user')->where([
            'token' => $request->header('X-Token')
        ])->first();

        if (empty($auth)) {
            return false;
        }

        $this->auth->setUser($auth->user);

        return true;
    }
}