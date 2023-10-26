<?php

namespace App\Providers;

use App\Services\View\ViewService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    protected ViewService $viewService;

    /**
    * Upon the registering of the ViewServiceProvider we are going to be hitching some data on for the ride onto all
    * rendering of views; we are going to be returning the view with $css, $js, $vs ViewService instant activity.
    *
    * @return void
    */
    public function register(): void
    {
        $this->viewService = app('vs');

        $this->setupBladeDirectives();

        View::composer('*', function ($view) {
            return $view->with([
                'vs'        => $this->viewService->all(),
                'bodyClass' => $this->getBodyClass()
            ]);
        });
    }

    /**
     * Get all the body classes together that the frontend will be using in order to render the page to the user in the
     * way that is needed. Inclusion of the theme class, whether the sidebar has been closed or not and more.
     *
     * @return string
     */
    private function getBodyClass(): string
    {
        return implode(' ', [
            $this->viewService->getCurrentPage(),
            $this->viewService->getHasSidebar() ? 'has-sidebar' : '',
            $this->viewService->getUser()?->user_setting?->sidebar_collapsed ? 'sidebar-closed' : ''
        ]);
    }

    private function setupBladeDirectives(): void
    {
        Blade::directive('cssAsset', function ($expression) {
            return $this->viewService->getCssAsset(str_replace(["'", "\""], '', $expression));
        });

        Blade::directive('jsAsset', function ($expression) {
            return $this->viewService->getJsAsset(str_replace(["'", "\""], '', $expression));
        });
    }

    public function boot(): void
    {

    }
}