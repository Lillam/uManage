@extends('template.master')
@section('css')
    {!! ($vs->css)('assets/vendor/datepicker/datepicker') !!}
    {!! ($vs->css)('views/time_log/view_time_log_calendar') !!}
@endsection
@section('js')
    {!! ($vs->js)('assets/vendor/datepicker/datepicker') !!}
    {!! ($vs->js)('views/time_log/view_time_log_calendar') !!}
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p class="date">
            <span>{{ $days->monday->format('d.m.Y') }} - {{ $days->sunday->format('d.m.Y') }}</span>
        </p>
    </div>
    <div class="uk-flex time_log_calendar_navigation navigation">
        <div class="uk-width-auto">
            <a class="time_log_calendar_left uk-button uk-icon-button fa fa-arrow-left"></a>
        </div>
        <div class="uk-width-auto">
            <a class="time_log_calendar_right uk-button uk-icon-button fa fa-arrow-right"></a>
        </div>
    </div>
@endsection
@section('sidebar')
    @include('library.time_log.time_log_sidebar')
@endsection
@section('body')
    <div class="time_log_calendar"
         data-view_time_logs_url="{{ route('time-logs.calendar.ajax') }}"
         data-delete_time_log_url="{{ route('time-log.delete.ajax') }}"
         data-current_date="{{ $date ?? $days->monday->format('d.m.Y') }}">
        <div class="time_logs"></div>
    </div>

    <div id="add_time_log_modal" uk-modal
         data-make_time_log_url="{{ route('time-log.create.ajax') }}"
         data-search_tasks_url="{{ route('projects.tasks.task.search.ajax') }}">
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title">Add Time Log</h2>
            </div>
            <div class="uk-modal-body" uk-overflow-auto>
                <form class="uk-form uk-grid uk-grid-small" uk-grid>
                    <div class="uk-width-1-1 task_search_input_wrapper">
                        <label for="task_id" class="uk-hidden">Task ID</label>
                        <input type="text"
                               id="task_id"
                               placeholder="Search issue..."
                               autocomplete="off"
                        />
                        <i class="fa fa-spin fa-spinner"></i>
                    </div>
                    <div class="uk-width-1-1">
                        <label for="time_log_note" class="uk-hidden">Time Log Note</label>
                        <textarea placeholder="Note..." id="time_log_note"></textarea>
                    </div>
                    <div class="uk-width-1-3">
                        <label for="time_spent" class="uk-hidden">Time Spent</label>
                        <input type="text"
                               id="time_spent"
                               placeholder="Time spent..."
                        />
                    </div>
                    <div class="uk-width-1-3">
                        <label for="from" class="uk-hidden">From</label>
                        <input type="text"
                               id="from"
                               placeholder="From..."
                        />
                    </div>
                    <div class="uk-width-1-3">
                        <label for="to" class="uk-hidden">To</label>
                        <input type="text"
                               id="to"
                               placeholder="To..."
                        />
                    </div>
                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
                <button class="uk-button uk-button-primary make_time_log" type="button">Save</button>
            </div>
        </div>
    </div>
@endsection