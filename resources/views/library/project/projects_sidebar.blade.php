<h2>Projects</h2>
<a class="{{ ($vs->is_page)('page.projects.dashboard') }}"
   href="{{ route('projects.dashboard') }}"><i class="fa fa-chart-pie"></i>Dashboard</a>
<a class="{{ ($vs->is_page)('page.projects.list') }}"
   href="{{ route('projects.list') }}"><i class="fa fa-list-alt"></i>List</a>
<a href=""><i class="fa fa-cogs"></i>Settings</a>
<h2>Tasks</h2>
<a href=""><i class="fa fa-chart-pie"></i>Dashboard</a>
<a class="{{ ($vs->is_page)('tasks.list') }}"
   href="{{ route('projects.tasks') }}"><i class="fa fa-list-alt"></i>List</a>