@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journal_report/view_journal_report') !!}
@endsection
@section('js')
    {!! ($vs->js)('assets/vendor/chart/chart') !!}
    {!! ($vs->js)('views/journal_report/view_journal_report') !!}
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p><span class="journal_report_date">{{ $date->format('F Y') }}</span></p>
    </div>
    {{-- Navigation: Click events applied to left and right buttons for cycling into the future and past
        dates so that you can view the differences between months --}}
    <div class="uk-flex journal_report_navigation">
        <div class="uk-width-auto">
            <a class="journal_report_calendar_left uk-button uk-icon-button fa fa-arrow-left"></a>
        </div>
        <div class="uk-width-auto">
            <a class="journal_report_calendar_right uk-button uk-icon-button fa fa-arrow-right"></a>
        </div>
    </div>
@endsection
@section('sidebar')
    @include('library.journal.journals_sidebar')
@endsection
@section('body')
    <div class="journal_reports"
        data-view_journals_report_url="{{ action('Web\Journal\JournalReportController@_ajaxViewJournalsReportGet') }}"
        data-date="{{ $date->format('Y-m') }}">
        <div class="section no-border-top">
            <h2 class="section_title">Overview</h2>

            <div class="uk-grid uk-grid-small day_ratings" uk-grid>
                <div class="uk-width-1-5@l uk-width-1-3@m uk-width-1-2@s uk-width-1-1">
                    <div class="day_rating bad uk-flex uk-flex-middle">
                        <p><span class="statistic_1_star_days">0</span>Bad Days</p>
                    </div>
                </div>
                <div class="uk-width-1-5@l uk-width-1-3@m uk-width-1-2@s uk-width-1-1">
                    <div class="day_rating poor uk-flex uk-flex-middle">
                        <p><span class="statistic_2_star_days">0</span>Poor Days</p>
                    </div>
                </div>
                <div class="uk-width-1-5@l uk-width-1-3@m uk-width-1-2@s uk-width-1-1">
                    <div class="day_rating average uk-flex uk-flex-middle">
                        <p><span class="statistic_3_star_days">0</span>Average Days</p>
                    </div>
                </div>
                <div class="uk-width-1-5@l uk-width-1-3@m uk-width-1-2@s uk-width-1-1">
                    <div class="day_rating good uk-flex uk-flex-middle">
                        <p><span class="statistic_4_star_days">0</span>Good Days</p>
                    </div>
                </div>
                <div class="uk-width-1-5@l uk-width-1-3@m uk-width-1-2@s uk-width-1-1">
                    <div class="day_rating amazing uk-flex uk-flex-middle">
                        <p><span class="statistic_5_star_days">0</span>Amazing Days</p>
                    </div>
                </div>
            </div>
            {{--                    <div class="total_achievements uk-flex uk-flex-middle">--}}
            {{--                        <div class="uk-width-expand">--}}
            {{--                            <i class="fa fa-icon fa-trophy"></i>--}}
            {{--                        </div>--}}
            {{--                        <div class="uk-width-auto uk-flex uk-flex-middle">--}}
            {{--                            <span class="statistic_achievements"></span>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
        </div>
        <div class="section">
            <h2 class="section_title">Graphing</h2>
            <div class="uk-grid uk-grid-medium" uk-grid>
                {{-- Graph: Canvas, Applied attributes for viewing the data url as well as the data that we are
                wanting to check data by. --}}
                <div class="uk-width-1-1@m">
                    <div class="uk-grid uk-grid-medium" uk-grid>
                        <div class="uk-width-1-2@l">
                            <div class="box">
                                <canvas class="journal_report_overview_graph"
                                        width="100vh"
                                        height="80vh"></canvas>
                            </div>
                        </div>
                        <div class="uk-width-1-2@l">
                            <div class="box">
                                <canvas class="journal_report_words_count_graph"
                                        width="100vh"
                                        height="80vh"></canvas>
                            </div>
                        </div>
                        <div class="uk-width-1-2@l">
                            <div class="box">
                                <canvas class="journal_report_rating_graph"
                                        width="100vh"
                                        height="80vh"></canvas>
                            </div>
                        </div>
                        <div class="uk-width-1-2@l">
                            <div class="box">
                                <canvas class="journal_report_achievements_graph"
                                        width="100vh"
                                        height="80vh"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection