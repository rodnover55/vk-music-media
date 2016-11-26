<?php

namespace VkMusic\Http\Controllers;

use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use VkMusic\Models\Token;

class Controller extends BaseController
{
    /** @var Container */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    protected function getToken(): Token {
        /** @var Request $request */
        $request = $this->container->make(Request::class);

        $token = $request->header('X-Token');

        return Token::where('token', $token)->first();
    }
}
