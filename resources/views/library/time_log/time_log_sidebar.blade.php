<h2>Time Logs</h2>
<a class="{{ ($vs->isPage)('page.time-logs.calendar') }}" href="{{ route('time-logs.calendar') }}">
    <i class="fa fa-calendar-alt"></i>{{ __('time_log.sidebar.calendar') }}
</a>
<a class="{{ ($vs->isPage)('page.time-logs.report') }}" href="{{ route('time-logs.report') }}">
    <i class="fa fa-chart-bar"></i>{{ __('time_log.sidebar.report') }}
</a>