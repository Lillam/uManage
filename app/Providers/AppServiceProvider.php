<?php

namespace App\Providers;

use App\Services\View\ViewService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Facade\IgnitionContracts\SolutionProviderRepository;
use Laravel\Telescope\TelescopeServiceProvider as LaravelTelescopeServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        $this->registerLiveServices();
        $this->registerDevelopmentServices();
    }

    /**
    * Here we are going to be registering some live oriented service providers to the application... which are more so
    * dedicated and specified towards production.
    *
    * @return void
    */
    public function registerLiveServices(): void
    {
        App::instance('vs', new ViewService);
    }

    /**
    * Here we are going to be registering some development oriented service providers to the application; if the
    * environment is not development, then we are just going to be returning there and not proceeding with rendering
    * anything to the application.
    *
    * @return void
    */
    public function registerDevelopmentServices(): void
    {
        if (! App::environment(config('app.dev.environments')))
            return;

        app(SolutionProviderRepository::class)
            ->registerSolutionProvider(ExceptionSolutionProvider::class);
    }

    /**
    * Bootstrap any application services.
    *
    * @return void
    */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}