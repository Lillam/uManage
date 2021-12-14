<?php

namespace App\Http\Controllers\Timelog;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Timelog\Timelog;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;

class TimelogReportController extends Controller
{
    /**
    * TimelogReportController constructor.
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * This method opens up the ability for being able to see the reporting page of timelogs... this is just going to
    * be returning a view, and the view comes with a date that is automatically injected onto the page so that when
    * the ajax runs on the page load, it'll find all of the reporting information regarding the timelogs in the month
    * specified.
    *
    * @param Request $request
    * @return Factory|View
    */
    public function _viewTimelogReportGet(Request $request): Factory|View
    {
        $date = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::now();

        $this->vs->set('title', '- Timelog Report')
            ->set('current_page', 'page.timelogs');

        return view('timelog.timelog_report.view_timelog_report', compact(
            'date'
        ));
    }

    /**
    * This method is the javascript hookup on the page, which will run off and grab everything that is needed on the
    * page, this method in particular is a snapshot of reporting at the current month in choice.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxViewTimelogReportGet(Request $request): JsonResponse
    {
        $date      = Carbon::parse($request->input('date'));
        $direction = $request->input('direction');

        if ($direction === 'left')
            $date = $date->subMonth(1);

        if ($direction === 'right')
            $date = $date->addMonth(1);

        $timelogs = Timelog::with(['task', 'project'])
            ->where('from', '>=', Carbon::parse($date)->startOfMonth())
            ->where('to', '<=', Carbon::parse($date)->endOfMonth())
            ->get();

        $projects = $tasks = $day_logs = [];

        // iterating over the timelogs that is against the user in question; so this will want to be revolving around
        // the currently signed in user; and iterate over all of their logs to gather a sense of how productive they're
        // being on a month to month
        foreach ($timelogs as $timelog) {
            // acquiring time by project...
            $timelog->project->time_logged   += round($timelog->time_spent / 60, 2);
            $projects[$timelog->project->id] = $timelog->project;

            // acquiring time by task...
            $timelog->task->time_logged += round($timelog->time_spent / 60, 2);
            $timelog->task->color       = $timelog->project->color;
            $timelog->task->code        = "{$timelog->project->code}-{$timelog->task->id}";
            $tasks[$timelog->task_id]   = $timelog->task;

            // acquiring time logging by day...
            if (! empty($day_logs[$timelog->from->format('d-m-Y')])) {
                $day_logs[$timelog->from->format('d-m-Y')] += round($timelog->time_spent / 60, 2);
                continue;
            }

            // if we haven't set it yet, then we are going to do that right here, otherwise if we did set it, we are
            // going to append to this above, and then just continue so this snippet of code doesn't get read.
            $day_logs[$timelog->from->format('d-m-Y')]  = round($timelog->time_spent / 60, 2);
        }

        $projects = collect($projects)->sortBy('time_logged');
        $tasks    = collect($tasks)->sortBy('time_logged');

        $project_labels = $projects->pluck('code')->toArray();
        $project_values = $projects->pluck('time_logged')->toArray();
        $project_colors = $projects->pluck('color')->toArray();

        $task_labels    = $tasks->pluck('code')->toArray();
        $task_values    = $tasks->pluck('time_logged')->toArray();
        $task_colors    = $tasks->pluck('color')->toArray();

        $daylog_labels  = array_keys($day_logs);
        $daylog_values  = array_values($day_logs);
        $daylog_colors  = array_fill(0, count($day_logs), '#40e0d0');

        return response()->json([
            // default date data...
            'date'           => $date->format('Y-m'),
            'display_date'   => $date->format('F Y'),
            // project time logging data...
            'project_labels' => $project_labels,
            'project_values' => $project_values,
            'project_colors' => $project_colors,
            // task item logging data...
            'task_labels'    => $task_labels,
            'task_values'    => $task_values,
            'task_colors'    => $task_colors,
            // day item logging data...
            'daylog_labels'  => $daylog_labels,
            'daylog_values'  => $daylog_values,
            'daylog_colors'  => $daylog_colors
        ]);
    }
}