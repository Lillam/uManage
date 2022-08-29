{{-- Projects Oriented Portion of the Projects --}}
<h2>Projects</h2>
<a class="{{ ($vs->isPage)('page.projects.dashboard') }}"
   href="{{ route('projects.dashboard') }}"><i class="fa fa-chart-pie"></i>{{ __('project.sidebar.dashboard') }}</a>
<a class="{{ ($vs->isPage)('page.projects.list') }}"
   href="{{ route('projects.list') }}"><i class="fa fa-list-alt"></i>{{ __('project.sidebar.list') }}</a>
<a class="{{ ($vs->isPage)('page.projects.settings') }}"
   href="{{ route('projects.settings') }}"><i class="fa fa-cogs"></i>{{ __('project.sidebar.settings') }}</a>
{{-- Tasks Oriented Portion of the Projects --}}
<h2>Tasks</h2>
<a class="{{ ($vs->isPage)('page.projects.tasks.dashboard') }}" href="{{ route('projects.tasks.dashboard') }}">
    <i class="fa fa-chart-pie"></i>{{ __('project.sidebar.dashboard') }}
</a>
<a class="{{ ($vs->isPage)('tasks.list') }}" href="{{ route('projects.tasks') }}">
    <i class="fa fa-list-alt"></i>{{ __('project.sidebar.list') }}
</a>
<a class="{{ ($vs->isPage)('page.projects.tasks.activity') }}" href="{{ route('project.tasks.activity') }}">
    <i class="fa fa-clipboard-list"></i>Activity Feed
</a>