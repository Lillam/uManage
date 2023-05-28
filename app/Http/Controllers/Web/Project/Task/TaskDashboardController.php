<?php

namespace App\Http\Controllers\Web\Project\Task;

use App\Http\Controllers\Web\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class TaskDashboardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function _viewTasksDashboardGet(): Application|Factory|View
    {
        $this->vs->set('hasTitle', false)
                 ->set('currentPage', 'page.projects.tasks.dashboard');

        return view('task.view_task_dashboard');
    }
}