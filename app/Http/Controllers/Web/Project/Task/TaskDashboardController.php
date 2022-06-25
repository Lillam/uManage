<?php

namespace App\Http\Controllers\Web\Project\Task;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class TaskDashboardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function _viewTasksDashboardGet(): Application|Factory|View
    {
        $this->vs->set('has_title', false)
                 ->set('current_page', 'page.projects.tasks.dashboard');

        return view('task.view_task_dashboard');
    }
}