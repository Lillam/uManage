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
        View::composer('*', function ($view) {
            return $view->with([
                'vs'                => $view_service = app('vs')->all(),
                'theme'             => $view_service->application_theme,
                'current_page'      => $view_service->current_page,
                'sidebar_class'     => $view_service->has_sidebar ? 'has-sidebar' : '',
                'sidebar_collapsed' => $view_service->user?->user_setting?->sidebar_collapsed ? 'sidebar-closed' : ''
            ]);
        });
    }

    public function boot()
    {

    }
}