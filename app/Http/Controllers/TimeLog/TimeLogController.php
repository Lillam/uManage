<?php

namespace App\Http\Controllers\TimeLog;

use Exception;
use Throwable;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\TimeLog\TimeLog;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use App\Helpers\DateTime\DateTimeHelper;
use App\Repositories\TimeLog\TimeLogRepository;

class TimeLogController extends Controller
{
    /**
    * This method is just for returning to the user, a visual of the days of the week, we will grab today's date. and
    * then we will acquire the start of the week and the end of the week. so regardless of where we are during the
    * week we are always going to be returning a visual to the user of (x) days 7... sunday to monday... and displaying
    * a 7-day week calendar every week, procedurally
    *
    * @return Factory|View
    */
    public function _viewTimeLogCalendarGet(): Factory|View
    {
        $days = (object) DateTimeHelper::days(Carbon::now());

        $this->vs->set('title', '- Time Logging')
                 ->set('current_page', 'page.time-logs.calendar');

        return view('time_log.view_time_log_calendar', compact(
            'days'
        ));
    }

    /**
    * This particular method returns json response that has a variety of information set back, this will be returning
    * the new date that we are in between range from, as well as the complete new html structure for the calendar.
    * as well as returning the new update title.
    *
    * @param Request $request
    * @return JsonResponse
    * @throws Throwable
    */
    public function _ajaxViewTimeLogsCalendarGet(Request $request): JsonResponse
    {
        $today = Carbon::now()->format('d-m-Y');

        $date = $request->input('date') !== 'false' ?
            Carbon::parse($request->input('date'))->startOfWeek() :
            Carbon::now()->startOfWeek();

        $direction = $request->input('direction');

        // if the direction has been set, and the direction has been specified to right, then we are looking to gather
        // time logs in the future, or, perhaps we've gone into the past and are attempting to come back to the present.
        // either way this is taking the dates, 7 days from the start of the week, so we have a perpetual movement of
        // dates into the future.
        if (! empty($direction) && $direction === 'right') {
            $date = $date->addDays(7);
        }

        // if the direction has been set and the direction has been specified to the left, then we are looking to gather
        // time logs in the past, or perhaps we have gone into the future and are attempting to come back to the present.
        // either way this is taking the dates 7 days from the start of the week; so we have a perpetual movement of
        // dates into the past.
        if (! empty($direction) && $direction === 'left') {
            $date = $date->subDays(7);
        }

        // build an object for the entire week in date format, so we are able to create the week view on the page.
        // with this we are able to have data attributes on the column in question.
        $days = (object) DateTimeHelper::days($date);

        // acquire all the time logs for the user of which is signed in, that are in between this particular monday, or
        // a monday that we have specified, and between this sunday, or the sunday that was the end of the week specified.
        $time_logs = TimeLog::query()
            ->with('task', 'project')
            ->where('from', '>=', $days->monday)
            ->where('to', '<=', $days->sunday)
            ->where('user_id', '=', Auth::id())
            ->get();

        // acquire the time log data, which will be sorted by the TimeLogRepository, this will be bringing back the total
        // amount of hours for the week in question, assorted by days from monday - sunday within the week.
        $time_log_data = TimeLogRepository::sortTimeLogs($time_logs);

        // after acquiring the time log data, we are returning an object with the following time_log_data_hours in the
        // format of:
        // monday->total_hours = 6800.
        // tuesday->total_hours = 7800.
        // as well as acquiring each day's daily log for the user in question.
        $time_log_hours = $time_log_data->time_log_date_hours;
        $time_logs      = $time_log_data->time_log_data;

        // return the above data in a json format so that the frontend is able to render all the visuals for the date
        // in question that the user has requested to see. this will also be returning an array of time logs, the
        // current date we are looking at, as well as the title difference which will be date-date,
        // 01-01-2019 - 01-02-2019
        return response()->json([
            'html' => view('library.time_log.view_time_logs', compact(
                'days',
                'time_logs',
                'time_log_hours',
                'today'
            ))->render(),
            'date'  => $days->monday->format('d.m.Y'),
            'title' => "{$days->monday->format('d.m.Y')} - {$days->sunday->format('d.m.Y')}",
        ]);
    }

    /**
    * Method for returning time logs as a universalised term, whenever time logs are needed this method will be called
    * to return a select amount of time logs to the specific element in question on the page (this is an ajax method
    * and should only ever be called in reference with an ajax method)
    *
    * @return JsonResponse
    * @throws Throwable
    */
    public function _ajaxViewTimeLogsGet(): JsonResponse
    {
        $time_logs = TimeLog::query()
            ->where('user_id', '=', Auth::id())
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'html' => view('library.time_log.ajax_view_time_logs', compact(
                'time_logs'
            ))->render()
        ]);
    }

    /**
    * This method is for saving new TimeLog posts. this method will be called every time the user is attempting to make
    * a new TimeLog entry, this method doesn't in truth need to return anything, providing this method doesn't return
    * an error, the javascript will refresh the Time Logging and will instantly show the new TimeLog entry...
    *
    * @param Request $request
    * @return void
    */
    public function _ajaxMakeTimeLogPost(Request $request): void
    {
        $task_id    = $request->input('task_id');
        $project_id = $request->input('project_id');

        // send this logic off the time_log repository so that we are able to more effectively manage how the handling of
        // time is captured for this particular method.
        $time_spent = TimeLogRepository::translateTimeSpent($request->input('time_spent'));

        // dates, we are going to be inserting when this was from and when it was to, so that we are able to handle the
        // ordering of these entries more accurately and quite possibly look towards displaying these in a timely
        // manner...
        $from          = $request->input('from') ? Carbon::parse($request->input('from')) : '';
        $to            = $request->input('to')     ? Carbon::parse($request->input('to')) : '';
        $time_log_note = $request->input('time_log_note') ?: '';

        // create the TimeLog entry based on all the information that is collected above, we aren't going to store this
        // information as there is no need to retain it for any form of return type, thus we are going to end here
        // assuming this does not error, the method will proceed with a void.
        TimeLog::query()->create([
            'task_id'    => $task_id,
            'project_id' => $project_id,
            'user_id'    => $this->vs->get('user')->id,
            'time_spent' => $time_spent,
            'from'       => $from,
            'to'         => $to,
            'note'       => $time_log_note
        ]);
    }

    /**
    * This method is simply for being able to delete the time_log in question; this method is going to allow the
    * deletion of a time_log and then return whether the user is allowed to do so or not, or some other unaccounted
    * error.
    *
    * @param Request $request
    * @return JsonResponse
    * @throws Exception
    */
    public function _ajaxDeleteTimeLogGet(Request $request): JsonResponse
    {
        $time_log = TimeLog::query()
            ->where('id', '=', $request->input('time_log_id'))
            ->first();

        $response = [
            'error' => 'Something went wrong...'
        ];

        if (! $time_log instanceof TimeLog) {
            $response = [
                'error' => 'The time_log you have requested to delete does not exist'
            ];
        }

        if ($this->vs->get('user')->can('TimeLogPolicy@editTimeLog', $time_log)) {
            $time_log->delete();

            $response = [
                'success' => 'The time_log has been successfully deleted'
            ];
        }

        return response()->json($response);
    }
}