<?php

namespace App\Http\Controllers\Web\TimeLog;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\TimeLog\TimeLog;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;

class TimeLogReportController extends Controller
{
    /**
    * This method opens up the ability for being able to see the reporting page of time logs... this is just going to
    * be returning a view, and the view comes with a date that is automatically injected onto the page so that when
    * the ajax runs on the page load, it'll find all the reporting information regarding the time logs in the month
    * specified.
    *
    * @param Request $request
    * @return Factory|View
    */
    public function _viewTimeLogReportGet(Request $request): Factory|View
    {
        $date = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::now();

        $this->vs->set('title', '- TimeLog Report')
            ->set('current_page', 'page.time-logs.report');

        return view('time_log.time_log_report.view_time_log_report', compact(
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
    public function _ajaxViewTimeLogReportGet(Request $request): JsonResponse
    {
        $date      = Carbon::parse($request->input('date'));
        $direction = $request->input('direction');

        if ($direction === 'left')
            $date = $date->subMonth(1);

        if ($direction === 'right')
            $date = $date->addMonth(1);

        $time_logs = TimeLog::with(['task', 'project'])
            ->where('from', '>=', Carbon::parse($date)->startOfMonth())
            ->where('to', '<=', Carbon::parse($date)->endOfMonth())
            ->get();

        $projects = $tasks = $day_logs = [];

        // iterating over the time logs that is against the user in question; so this will want to be revolving around
        // the currently authenticated user; and iterate over all of their logs to gather a sense of how productive
        // they're being on a month to month
        foreach ($time_logs as $time_log) {
            // acquiring time by project...
            $time_log->project->time_logged   += round($time_log->time_spent / 60, 2);
            $projects[$time_log->project->id] = $time_log->project;

            // acquiring time by task...
            $time_log->task->time_logged += round($time_log->time_spent / 60, 2);
            $time_log->task->color       = $time_log->project->color;
            $time_log->task->code        = "{$time_log->project->code}-{$time_log->task->id}";
            $tasks[$time_log->task_id]   = $time_log->task;

            // acquiring time logging by day...
            if (! empty($day_logs[$time_log->from->format('d-m-Y')])) {
                $day_logs[$time_log->from->format('d-m-Y')] += round($time_log->time_spent / 60, 2);
                continue;
            }

            // if we haven't set it yet, then we are going to do that right here, otherwise if we did set it, we are
            // going to append to this above, and then just continue so this snippet of code doesn't get read.
            $day_logs[$time_log->from->format('d-m-Y')]  = round($time_log->time_spent / 60, 2);
        }

        $projects = collect($projects)->sortBy('time_logged');
        $tasks    = collect($tasks)->sortBy('time_logged');

        $project_labels = $projects->pluck('code')->toArray();
        $project_values = $projects->pluck('time_logged')->toArray();
        $project_colors = $projects->pluck('color')->toArray();

        $task_labels    = $tasks->pluck('code')->toArray();
        $task_values    = $tasks->pluck('time_logged')->toArray();
        $task_colors    = $tasks->pluck('color')->toArray();

        $day_log_labels  = array_keys($day_logs);
        $day_log_values  = array_values($day_logs);
        $day_log_colors  = array_fill(0, count($day_logs), '#40e0d0');

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
            'daylog_labels'  => $day_log_labels,
            'daylog_values'  => $day_log_values,
            'daylog_colors'  => $day_log_colors
        ]);
    }
}