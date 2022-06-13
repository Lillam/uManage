@extends('template.master')
@section('css')
    {!! ($vs->css)('assets/vendor/datepicker/datepicker') !!}
    {!! ($vs->css)('views/timelog/view_timelog_calendar') !!}
@endsection
@section('js')
    {!! ($vs->js)('assets/vendor/datepicker/datepicker') !!}
    {!! ($vs->js)('views/timelog/view_timelog_calendar') !!}
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p class="date">
            <span>{{ $days->monday->format('d.m.Y') }} - {{ $days->sunday->format('d.m.Y') }}</span>
        </p>
    </div>
    <div class="uk-flex timelog_calendar_navigation">
        <div class="uk-width-auto">
            <a class="timelog_calendar_left uk-button uk-icon-button fa fa-arrow-left"></a>
        </div>
        <div class="uk-width-auto">
            <a class="timelog_calendar_right uk-button uk-icon-button fa fa-arrow-right"></a>
        </div>
    </div>
@endsection
@section('sidebar')
    @include('library.timelog.timelog_sidebar')
@endsection
@section('body')
    <div class="timelog_calendar"
         data-view_timelogs_url="{{ action('TimeLog\TimeLogController@_ajaxViewTimelogsCalendarGet') }}"
         data-delete_timelog_url="{{ action('TimeLog\TimeLogController@_ajaxDeleteTimelogGet') }}"
         data-current_date="{{ $days->monday->format('d.m.Y') }}">
        <div class="timelogs"></div>
    </div>

    <div id="add_timelog_modal" uk-modal
        data-make_timelog_url="{{ action('TimeLog\TimeLogController@_ajaxMakeTimelogPost') }}"
        data-search_tasks_url="{{ action('Task\TaskController@_ajaxSearchTasksGet') }}">
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title">Add Timelog</h2>
            </div>
            <div class="uk-modal-body" uk-overflow-auto>
                <form class="uk-form uk-grid uk-grid-small" uk-grid>
                    <div class="uk-width-1-1 task_search_input_wrapper">
                        <input type="text" id="task_id" placeholder="Search issue..." autocomplete="off">
                        <i class="fa fa-spin fa-spinner"></i>
                    </div>
                    <div class="uk-width-1-1">
                        <textarea placeholder="Note..." id="timelog_note"></textarea>
                    </div>
                    <div class="uk-width-1-3">
                        <input type="text" id="time_spent" placeholder="Time spent...">
                    </div>
                    <div class="uk-width-1-3">
                        <input type="text" id="from" placeholder="From...">
                    </div>
                    <div class="uk-width-1-3">
                        <input type="text" id="to" placeholder="To...">
                    </div>
                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary make_timelog" type="button">Save</button>
            </div>
        </div>
    </div>
@endsection