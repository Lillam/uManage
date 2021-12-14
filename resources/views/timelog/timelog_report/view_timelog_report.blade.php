@extends('template.master')
@section('css')
    {!! ($vs->css)('views/timelog/timelog_report/view_timelog_report') !!}
@endsection
@section('js')
    {!! ($vs->js)('assets/vendor/chart/chart') !!}
    {!! ($vs->js)('views/timelog/timelog_report/view_timelog_report') !!}
@endsection
@section('body')
    <div class="timelog_report_navigation">
        <div class="uk-flex">
            <div class="uk-width-expand uk-flex uk-flex-middle">
                <span class="timelog_report_date">{{ $date->format('F Y') }}</span>
            </div>
            <div class="uk-width-auto uk-border-left">
                <a class="timelog_report_navigation_left fa fa-arrow-left"></a>
            </div>
            <div class="uk-width-auto uk-border-left">
                <a class="timelog_report_navigation_right fa fa-arrow-right"></a>
            </div>
        </div>
    </div>

    <div class="uk-container timelog_reports"
         data-view_timelog_reports_url="{{ action('Timelog\TimelogReportController@_ajaxViewTimelogReportGet') }}"
         data-date="{{ $date->format('Y-m') }}">
        <div class="uk-grid uk-grid-small" uk-grid>
            <div class="uk-width-1-3@m">
                <div class="box">
                    <canvas class="timelog_report_by_project" width="100vh" height="77vh"></canvas>
                </div>
                <div class="box uk-margin-top">
                    <canvas class="timelog_report_by_day uk-margin-top" width="100vh" height="77vh"></canvas>
                </div>
            </div>
            <div class="uk-width-2-3@m">
                <div class="box">
                    <canvas class="timelog_report_by_task" width="100vh" height="78vh"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection