<?php

namespace App\Http\Controllers\Web\Project;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class ProjectDashboardController extends Controller
{
    /**
     * Method for viewing the dashboard of all things project related. The home landing screen which will be the
     * handler for showing the authenticated user's project information.
     *
     * @return Application|Factory|View
     */
    public function _viewProjectsDashboardGet(): Application|Factory|View
    {
        $this->vs->set('has_title', false)
                 ->set('title', '- Projects - Dashboard')
                 ->set('current_page', 'page.projects.dashboard');

        return view('project.view_project_dashboard');
    }
}