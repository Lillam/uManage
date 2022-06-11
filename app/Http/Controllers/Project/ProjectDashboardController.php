<?php

namespace App\Http\Controllers\Project;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class ProjectDashboardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function _viewProjectsDashboardGet(): Application|Factory|View
    {
        $this->vs->set('has_sidebar', true)
                 ->set('current_page', 'page.projects.dashboard');

        return view('project.view_project_dashboard');
    }
}