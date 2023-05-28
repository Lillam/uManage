<?php

namespace App\Http\Controllers\Web\TimeLog;

use App\Helpers\DateTime\DateTimeHelper;
use App\Http\Controllers\Web\Controller;
use App\Models\TimeLog\TimeLog;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimeLogReportController extends Controller
{
    /**
    * This method opens up the ability for being able to see the reporting page of time logs... this is just going to
    * be returning a view, and the view comes with a date that is automatically injected onto the page so that when
    * the ajax runs on the page load, it'll find all the reporting information regarding the time logs in the month
    * specified.
    *
    * @param Request $request
    * @return Application|Factory|View
    */
    public function _viewTimeLogReportGet(Request $request): Application|Factory|View
    {
        $date = DateTimeHelper::nowOrDate($request->input('date'));

        $this->vs->set('title', '- TimeLog Report')
                 ->set('currentPage', 'page.time-logs.report');

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
        $date = DateTimeHelper::moveDateByMonths(
            Carbon::parse($request->input('date')),
            $request->input('direction')
        );

        $timeLogs = TimeLog::query()
            ->with(['task', 'project'])
            ->where('from', '>=', Carbon::parse($date)->startOfMonth())
            ->where('to', '<=', Carbon::parse($date)->endOfMonth())
            ->get();

        $projects = $tasks = $dayLogs = [];

        // iterating over the time logs that is against the user in question; so this will want to be revolving around
        // the currently authenticated user; and iterate over all of their logs to gather a sense of how productive
        // they're being on a month to month
        foreach ($timeLogs as $timeLog) {
            // acquiring time by project...
            $timeLog->project->time_logged   += round($timeLog->time_spent / 60, 2);
            $projects[$timeLog->project->id] = $timeLog->project;

            // acquiring time by task...
            $timeLog->task->time_logged += round($timeLog->time_spent / 60, 2);
            $timeLog->task->color       = $timeLog->project->color;
            $timeLog->task->code        = "{$timeLog->project->code}-{$timeLog->task->id}";
            $tasks[$timeLog->task_id]   = $timeLog->task;

            // acquiring time logging by day...
            if (! empty($dayLogs[$timeLog->from->format('d-m-Y')])) {
                $dayLogs[$timeLog->from->format('d-m-Y')] += round($timeLog->time_spent / 60, 2);
                continue;
            }

            // if we haven't set it yet, then we are going to do that right here, otherwise if we did set it, we are
            // going to append to this above, and then just continue so this snippet of code doesn't get read.
            $dayLogs[$timeLog->from->format('d-m-Y')]  = round($timeLog->time_spent / 60, 2);
        }

        $projects = collect($projects)->sortBy('time_logged');
        $tasks    = collect($tasks)->sortBy('time_logged');

        $projectLabels = $projects->pluck('code')->toArray();
        $projectValues = $projects->pluck('time_logged')->toArray();
        $projectColors = $projects->pluck('color')->toArray();

        $taskLabels    = $tasks->pluck('code')->toArray();
        $taskValues    = $tasks->pluck('time_logged')->toArray();
        $taskColors    = $tasks->pluck('color')->toArray();

        $dayLogLabels  = array_keys($dayLogs);
        $dayLogValues  = array_values($dayLogs);
        $dayLogColors  = array_fill(0, count($dayLogs), '#40e0d0');

        return response()->json([
            // default date data...
            'date'           => $date->format('Y-m'),
            'display_date'   => $date->format('F Y'),
            // project time logging data...
            'project_labels' => $projectLabels,
            'project_values' => $projectValues,
            'project_colors' => $projectColors,
            // task item logging data...
            'task_labels'    => $taskLabels,
            'task_values'    => $taskValues,
            'task_colors'    => $taskColors,
            // day item logging data...
            'daylog_labels'  => $dayLogLabels,
            'daylog_values'  => $dayLogValues,
            'daylog_colors'  => $dayLogColors
        ]);
    }
}