<h2>Journals</h2>

<h3>Daily Journals</h3>
<a class="{{ ($vs->isPage)('page.journals.dashboard') }}" href="{{ route('journals.dashboard') }}">
    <i class="fa fa-chart-pie"></i>{{ __('journal.sidebar.daily.dashboard') }}
</a>
<a class="{{ ($vs->isPage)('page.journals.calendar') }}" href="{{ route('journals.calendar') }}">
    <i class="fa fa-calendar-alt"></i>{{ __('journal.sidebar.daily.calendar') }}
</a>
<a class="{{ ($vs->isPage)('page.journals.journal.today') }}"
   href="{{ route('journals.journal', (new DateTime)->format('Y-m-d')) }}">
    <i class="fa fa-calendar-alt"></i>{{ __('journal.sidebar.daily.today') }}
</a>
<a class="{{ ($vs->isPage)('page.journals.report') }}" href="{{ route('journals.report') }}">
    <i class="fa fa-chart-bar"></i>{{ __('journal.sidebar.daily.report') }}
</a>

<hr />

<h3>Dream Journals</h3>
<a class="{{ ($vs->isPage)('page.journals.dreams.dashboard') }}" href="{{ route('journals.dreams.dashboard') }}">
    <i class="fa fa-chart-pie"></i>{{ __('journal.sidebar.dream.dashboard') }}
</a>
<a class="{{ ($vs->isPage)('page.journals.dreams.calendar') }}" href="{{ route('journals.dreams.calendar') }}">
    <i class="fa fa-calendar-alt"></i>{{ __('journal.sidebar.dream.calendar') }}
</a>
<hr />

<h3>Financial Journals</h3>
<a class="{{ ($vs->isPage)('page.journals.finances.dashboard') }}" href="{{ route('journals.finances.dashboard') }}">
    <i class="fa fa-chart-pie"></i>{{ __('journal.sidebar.finance.dashboard') }}
</a>
<a class="{{ ($vs->isPage)('page.journals.finances.calendar') }}" href="{{ route('journals.finances.calendar') }}">
    <i class="fa fa-calendar-alt"></i>{{ __('journal.sidebar.finance.calendar') }}
</a>