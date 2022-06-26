@extends('template.master')
@section('css')
    {!! ($vs->css)('views/time_log/time_log_report/view_time_log_report') !!}
@endsection
@section('js')
    {!! ($vs->js)('assets/vendor/chart/chart') !!}
    {!! ($vs->js)('views/time_log/time_log_report/view_time_log_report') !!}
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <span class="time_log_report_date">{{ $date->format('F Y') }}</span>
    </div>
    <div class="uk-flex time_log_report_navigation">
        <div class="uk-width-auto">
            <a class="time_log_report_navigation_left uk-button uk-icon-button fa fa-arrow-left"></a>
        </div>
        <div class="uk-width-auto">
            <a class="time_log_report_navigation_right uk-button uk-icon-button fa fa-arrow-right"></a>
        </div>
    </div>
@endsection
@section('sidebar')
    @include('library.time_log.time_log_sidebar')
@endsection
@section('body')
    <div class="uk-container time_log_reports"
         data-view_time_log_reports_url="{{ route('time-logs.report.ajax') }}"
         data-date="{{ $date->format('Y-m') }}">
        <div class="uk-grid uk-grid-small" uk-grid>
            <div class="uk-width-1-3@m">
                <div class="box">
                    <canvas class="time_log_report_by_project"
                            width="100vh"
                            height="77vh"
                    ></canvas>
                </div>
                <div class="box uk-margin-top">
                    <canvas class="time_log_report_by_day uk-margin-top"
                            width="100vh"
                            height="77vh"
                    ></canvas>
                </div>
            </div>
            <div class="uk-width-2-3@m">
                <div class="box">
                    <canvas class="time_log_report_by_task"
                            width="100vh"
                            height="78vh"
                    ></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection