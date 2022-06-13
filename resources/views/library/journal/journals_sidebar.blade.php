<h2>Journals</h2>

<h3>Daily Journals</h3>
<a class="{{ ($vs->is_page)('page.journals.dashboard') }}"
   href="{{ route('journals.dashboard') }}"><i class="fa fa-chart-pie"></i>Dashboard</a>
<a class="{{ ($vs->is_page)('page.journals.calendar') }}"
   href="{{ route('journals.calendar') }}"><i class="fa fa-calendar-alt"></i>Calendar</a>
<a class="{{ ($vs->is_page)('page.journals.journal.today') }}"
   href="{{ route('journals.journal', (new DateTime)->format('Y-m-d')) }}"><i class="fa fa-calendar-alt"></i>Today</a>
<a class="{{ ($vs->is_page)('page.journals.report') }}"
   href="{{ route('journals.report') }}"><i class="fa fa-chart-bar"></i>Report</a>

<hr />
<h3>Dream Journals</h3>
<a class="{{ ($vs->is_page)('page.journals.dreams.dashboard') }}"
   href="{{ route('journals.dreams.dashboard') }}"><i class="fa fa-chart-pie"></i>Dashboard</a>
<a class="{{ ($vs->is_page)('page.journals.dreams.calendar') }}"
   href="{{ route('journals.dreams.calendar') }}"><i class="fa fa-calendar-alt"></i>Calendar</a>

<hr />
<h3>Financial Journals</h3>
<a class="{{ ($vs->is_page)('page.journals.finances.dashboard') }}"
   href="{{ route('journals.finances.dashboard') }}"><i class="fa fa-chart-pie"></i>Dashboard</a>
<a class="{{ ($vs->is_page)('page.journals.finances.calendar') }}"
   href="{{ route('journals.finances.calendar') }}"><i class="fa fa-calendar-alt"></i>Calendar</a>