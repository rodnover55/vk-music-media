<?php

namespace VkMusic\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes()
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);

        $router->get('/{route}', function () {
            require base_path('public/index.html');
        })->where('route', '(.*)');
    }

    protected function mapApiRoutes()
    {
        /** @var Router $router */
        $router = $this->app->make(Router::class);

        $router->group([
            'middleware' => 'api',
            'namespace' => '',
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
