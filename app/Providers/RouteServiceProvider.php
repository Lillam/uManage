<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
    * This namespace is applied to your controller routes.
    *
    * In addition, it is set as the URL generator's root namespace.
    *
    * @var string
    */
    protected $namespace = 'App\Http\Controllers';

    /**
    * The path to the "home" route for your application.
    *
    * @var string
    */
    public const HOME = '/dashboard';

    /**
    * Define your route model bindings, pattern filters, etc.
    *
    * @return void
    */
    public function boot(): void
    {
        parent::boot();
    }

    /**
    * Define the routes for the application.
    *
    * @return void
    */
    public function map(): void
    {
        $this->mapModuleWebRoutes();
        $this->mapModuleApiRoutes();
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
    * Define the core "web" routes for the application. These routes all receive session state, CSRF protection, etc.
    * these routes would be considered public facing routes, routes that don't need the user to be authenticated and
    * can be accessed without being signed in.
    *
    * @return void
    */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->namespace($this->namespace)
                                          ->group(base_path('routes/core.web.php'));
    }

    /**
    * Acquire all the module web routes from the modules directory of /routes. Iterate over them and apply the web
    * routes into the routing making it easier for the developer to segment routing.
    *
    * @return void
    */
    protected function mapModuleWebRoutes(): void
    {
        foreach (config('module_routing.web') as $web_route_file) {
            Route::middleware(['web', 'auth', 'auth_user', 'module_check'])->namespace($this->namespace)
                                                                           ->group(base_path($web_route_file));
        }
    }

    /**
    * Acquire all the module api routes from the modules directory of /routes. Iterate over them and apply the api
    *routes into the routing, making it easier for the developer to segment routing.
    *
    * @return void
    */
    protected function mapModuleApiRoutes(): void
    {
        foreach (config('module_routing.api') as $api_route_file) {
            Route::prefix('api')->middleware('api')
                                      ->namespace($this->namespace)
                                      ->group(base_path($api_route_file));
        }
    }

    /**
    * Define the "api" routes for the application. These routes are typically stateless. all these routes would be
    * considered public facing api routes that don't need any form of authentication.
    *
    * @return void
    */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')->middleware('api')
                                  ->namespace($this->namespace)
                                  ->group(base_path('routes/core.api.php'));
    }
}
