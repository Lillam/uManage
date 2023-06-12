<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
    * Upon the registering of the ViewServiceProvider we are going to be hitching some data on for the ride onto all
    * rendering of views; we are going to be returning the view with $css, $js, $vs ViewService instant activity.
    *
    * @return void
    */
    public function register(): void
    {
        View::composer('*', fn ($view) => $view->with([
            'vs'        => $viewService = app('vs')->all(),
            'bodyClass' => $this->getBodyClass($viewService)
        ]));
    }

    /**
     * Get all the body classes together that the frontend will be using in order to render the page to the user in the
     * way that is needed. Inclusion of the theme class, whether the sidebar has been closed or not and more.
     *
     * @param object $viewService
     * @return string
     */
    private function getBodyClass(object $viewService): string
    {
        return implode(' ', [
            $viewService->currentPage,
            $viewService->hasSidebar ? 'has-sidebar' : '',
            $viewService->user?->user_setting?->sidebar_collapsed ? 'sidebar-closed' : ''
        ]);
    }

    public function boot(): void
    {

    }
}