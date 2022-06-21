<?php

namespace App\Providers;

use SplFileInfo;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use RecursiveCallbackFilterIterator;
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
        $this->mapModuleRoutes();
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
    * Define the core "web" routes for the application. These routes all receive session state, CSRF protection, etc.
    *
    * @return void
    */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->namespace($this->namespace)
                                          ->group(base_path('routes/core.web.php'));
    }

    /**
     * Recursively find all the files within routes/modules and begin mapping them into their necessary routes;
     * accordingly with their corresponding type.
     *
     * todo This needs modifying; I'm not 100% sold on the way that the whole recursively grabbing files works whilst it
     *      is nice to have the dynamic nature to it, I just don't envy the way that this is written. Probably consider
     *      something more manual for the time being. (appending module route files into the modules.php).
     *
     * *.web.php will be mapped as web routes
     * *.api.php will be mapped as api routes
     * *.console.php will be mapped as console routes
     * *.channels.php will be mapped as channel routes.
     *
     * @return void
     */
    protected function mapModuleRoutes(): void
    {
        foreach (new RecursiveIteratorIterator(
            new RecursiveCallbackFilterIterator(
                new RecursiveDirectoryIterator(base_path('routes/modules')),
                function (SplFileInfo $current): bool {
                    return $current->getFilename()[0] !== '.';
                }
            )
        ) as $file) {
            Route::middleware(['web', 'auth', 'auth_user', 'module_check'])->namespace($this->namespace)
                                                                           ->group($file->getRealPath());
        }
    }

    /**
    * Define the "api" routes for the application. These routes are typically stateless.
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
