<?php

namespace App\Http\Controllers\Timelog;

use Exception;
use Throwable;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Timelog\Timelog;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use App\Helpers\DateTime\DateTimeHelper;
use Illuminate\Support\Collection;
use App\Repositories\Timelog\TimelogRepository;

class TimelogController extends Controller
{
    /**
    * TimelogController constructor.
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * This method is just for returning to the user, a visual of the days of the week, we will grab todays date. and
    * then we will acquire the start of the week and the end of the week. so regardless of where we are during the
    * week we are always going to be returning a visual to the user of (x) days 7... sunday to monday... and displaying
    * a 7 day week calendar every week, procedurally
    *
    * @param Request $request
    * @return Factory|View
    */
    public function _viewTimelogCalendarGet(Request $request): Factory|View
    {
        $days = (object) DateTimeHelper::days(Carbon::now());

        $this->vs->set('title', '- Timelogging')
                 ->set('current_page', 'page.timelogs');

        return view('timelog.view_timelog_calendar', compact(
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
    public function _ajaxViewTimelogsCalendarGet(Request $request): JsonResponse
    {
        $today = Carbon::now()->format('d-m-Y');

        $date = $request->input('date') !== 'false' ?
            Carbon::parse($request->input('date'))->startOfWeek() :
            Carbon::now()->startOfWeek();

        $direction = $request->input('direction');

        // if the direction has been set, and the direction has been specified to right, then we are looking to gather
        // timelogs in the future, or, perhaps we've gone into the past and are attempting to come back to the present.
        // either way this is t aking the dates, 7 days from the start of the week, so we have a perpetual movement of
        // dates into the future.
        if (! empty($direction) && $direction === 'right') {
            $date = $date->addDays(7);
        }

        // if the direction has been set and the direction has been specified to the left, then we are looking to gather
        // timelogs in the past, or perhaps we have gone into the future and are attempting to come back to the present.
        // either way this is taking the dates 7 days from the start of the week so we have a perpetual movement of
        // dates into the past.
        if (! empty($direction) && $direction === 'left') {
            $date = $date->subDays(7);
        }

        // build an object for the entire week in date format, so we are able to create the week view on the page.
        // with this we are able to have data attributes on the column in question.
        $days = (object) DateTimeHelper::days($date);

        // acquire all the timelogs for the user of which is signed in, that are in between this particular monday, or
        // a monday that we have specified, and between this sunday, or the sunday that was the end of the week specified.
        $timelogs = Timelog::with('task', 'project')
            ->where('from', '>=', $days->monday)
            ->where('to', '<=', $days->sunday)
            ->where('user_id', '=', Auth::id())
            ->get();

        // acquire the timelog data, which will be sorted by the TimelogRepository, this will be bringing back the total
        // amount of hours for the week in question, assorted by days from monday - sunday within the week.
        $timelog_data = TimelogRepository::sortTimelogs($timelogs);

        // after ac1uiring the timelog data, we are returning an object with the following timelog_data_hours in the
        // format of:
        // monday->total_hours = 6800.
        // tuesday->total_hours = 7800.
        // as well as acquiring each day's daily log for the user in question.
        $timelog_hours = $timelog_data->timelog_date_hours;
        $timelogs      = $timelog_data->timelog_data;

        // return the above data in a json format so that the frontend is able to render all of the visuals for the date
        // in question that the user has requested to see. this will also be returning an array of timelogs, the current
        // date we are looking at, as well as the title difference which will be date-date, 01-01-2019 - 01-02-2019
        return response()->json([
            'html' => view('library.timelog.view_timelogs', compact(
                'days',
                'timelogs',
                'timelog_hours',
                'today'
            ))->render(),
            'date'  => $days->monday->format('d.m.Y'),
            'title' => "{$days->monday->format('d.m.Y')} - {$days->sunday->format('d.m.Y')}",
        ]);
    }

    /**
    * Method for returning timelogs as a universalised term, whenever timelogs are needed this method will be called to
    * return a select amount of timelogs to the specific element in question on the page (this is an ajax method and
    * should only ever be called in reference with an ajax method)
    *
    * @param Request $request
    * @return JsonResponse
    * @throws Throwable
    */
    public function _ajaxViewTimelogsGet(Request $request): JsonResponse
    {
        $timelogs = Timelog::where('user_id', '=', Auth::id())
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'html' => view('library.timelog.ajax_view_timelogs', compact(
                'timelogs'
            ))->render()
        ]);
    }

    /**
    * This method is for saving new Timelog posts. this method will be called every time the user is attempting to make
    * a new Timelog entry, this method doesn't in truth need to return anything, providing this method doesn't return
    * an error, the javascript will refresh the Timelogging and will instantly show the new Timelog entry...
    *
    * @param Request $request
    * @return void
    */
    public function _ajaxMakeTimelogPost(Request $request): void
    {
        $task_id    = $request->input('task_id');
        $project_id = $request->input('project_id');

        // send this logic off the timelog repository so that we are able to more effectively manage how the handling of
        // time is captured for this particular method.
        $time_spent = TimelogRepository::translateTimespent($request->input('time_spent'));

        // dates, we are going to be inserting when this was from and when it was to, so that we are able to handle the
        // ordering of these entries more accurately and quite possibly look towards displaying these in a timely
        // manner...
        $from = $request->input('from') ? Carbon::parse($request->input('from')) : '';
        $to = $request->input('to')     ? Carbon::parse($request->input('to')) : '';

        $timelog_note = $request->input('timelog_note') ?: '';

        // create the Timelog entry based on all of the information that is collected above, we aren't going to store
        // this information as there is no need to retain it for any form of return type, thus we are going to end here
        // assuming this does not error, the method will proceed with a void.
        Timelog::create([
            'task_id'    => $task_id,
            'project_id' => $project_id,
            'user_id'    => $this->vs->get('user')->id,
            'time_spent' => $time_spent,
            'from'       => $from,
            'to'         => $to,
            'note'       => $timelog_note
        ]);
    }

    /**
    * This method is simply for being able to delete the timelog in question; this method is going to allow the
    * deletion of a timelog and then return whether or not the user is allowed to do so, or some other unaccounted
    * error.
    *
    * @param Request $request
    * @return JsonResponse
    * @throws Exception
    */
    public function _ajaxDeleteTimelogGet(Request $request): JsonResponse
    {
        $timelog = Timelog::where('id', '=', $request->input('timelog_id'))->first();

        if (! $timelog instanceof Timelog) {
            return response()->json(['error' => 'The timelog you have requested to delete does not exist']);
        }

        if ($this->vs->get('user')->can('TimelogPolicy@editTimelog', $timelog)) {
            $timelog->delete();
            return response()->json([ 'success' => 'The timelog has been successfully deleted' ]);
        }

        return response()->json([ 'error' => 'Something went wrong...' ]);
    }
}