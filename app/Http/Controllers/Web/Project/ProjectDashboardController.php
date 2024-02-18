<?php

namespace App\Http\Controllers\Web\Project;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
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
        $this->vs->set('hasTitle', false)
                 ->set('title', '- Projects - Dashboard')
                 ->set('currentPage', 'page.projects.dashboard');

        return view('project.view_project_dashboard');
    }
}
