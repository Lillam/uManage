@extends('template.master')
@section('css')
    {!! ($vs->css)('views/journal_report/view_journal_report') !!}
@endsection
@section('js')
    {!! ($vs->js)('assets/vendor/chart/chart') !!}
    {!! ($vs->js)('views/journal_report/view_journal_report') !!}
@endsection
@section('body')
    {{-- Navigation: Click events applied to left and right buttons for cycling into the future and past
        dates so that you can view the differences between months --}}
    <div class="journal_report_navigation">
        <div class="uk-flex">
            <div class="uk-width-expand uk-flex uk-flex-middle">
                <span class="journal_report_date">{{ $date->format('F Y') }}</span>
            </div>
            <div class="uk-width-auto uk-border-left">
                <a class="journal_report_calendar_left fa fa-arrow-left"></a>
            </div>
            <div class="uk-width-auto uk-border-left">
                <a class="journal_report_calendar_right fa fa-arrow-right"></a>
            </div>
        </div>
    </div>
    <div class="uk-container journal_reports"
        data-view_journals_report_url="{{ action('Journal\JournalReportController@_ajaxViewJournalsReportGet') }}"
        data-date="{{ $date->format('Y-m') }}">
        <div class="uk-grid uk-grid-medium" uk-grid>
            <div class="uk-width-1-3@m">
                <div class="uk-grid uk-grid-small" uk-grid>
                    {{-- Reporting: Total Achievements --}}
                    <div class="uk-width-1-1">
                        <div class="total_achievements uk-flex uk-flex-middle">
                            <div class="uk-width-expand">
                                <i class="fa fa-icon fa-trophy"></i>
                            </div>
                            <div class="uk-width-auto uk-flex uk-flex-middle">
                                <span class="statistic_achievements"></span>
                            </div>
                        </div>
                    </div>
                    {{-- Reporting: Total 1 Star Days --}}
                    <div class="uk-width-1-1">
                        <div class="total_1_star_days uk-flex uk-flex-middle">
                            <div class="uk-width-expand">
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="uk-width-auto">
                                <span class="statistic_1_star_days"></span>
                            </div>
                        </div>
                    </div>
                    {{-- Reporting: Total 2 Star Days --}}
                    <div class="uk-width-1-1">
                        <div class="total_2_star_days uk-flex uk-flex-middle">
                            <div class="uk-width-expand">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="uk-width-auto">
                                <span class="statistic_2_star_days"></span>
                            </div>
                        </div>
                    </div>
                    {{-- Reporting: Total 3 Star Days --}}
                    <div class="uk-width-1-1">
                        <div class="total_3_star_days uk-flex uk-flex-middle">
                            <div class="uk-width-expand">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="uk-width-auto">
                                <span class="statistic_3_star_days"></span>
                            </div>
                        </div>
                    </div>
                    {{-- Reporting: Total 4 Star Days --}}
                    <div class="uk-width-1-1">
                        <div class="total_4_star_days uk-flex uk-flex-middle">
                            <div class="uk-width-expand">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="uk-width-auto">
                                <span class="statistic_4_star_days"></span>
                            </div>
                        </div>
                    </div>
                    {{-- Reporting: Total 5 Star Days --}}
                    <div class="uk-width-1-1">
                        <div class="total_5_star_days uk-flex uk-flex-middle">
                            <div class="uk-width-expand">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="uk-width-auto">
                                <span class="statistic_5_star_days"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Graph: Canvas, Applied attributes for viewing the data url as well as the data that we are
            wanting to check data by. --}}
            <div class="uk-width-2-3@m">
                <div class="uk-grid uk-grid-small" uk-grid>
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
@endsection