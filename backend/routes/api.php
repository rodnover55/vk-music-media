<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use VkMusic\Http\Controllers;

/** @var Router $router */

$router->resource('/token', Controllers\TokenController::class);
$router->resource('/posts-refresh', Controllers\PostsRefreshController::class);
$router->resource('/posts', Controllers\PostController::class);
