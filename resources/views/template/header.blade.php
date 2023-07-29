@if ($vs->hasHeader)
    <header>
        <ul>
            <li class="app-logo uk-height-expand">
                <a href="{{ route('user.dashboard') }}">u<span>M</span></a>
            </li>
            <li>
                <a class="{{ ($vs->isPage)('page.dashboard') }}" href="{{ route('user.dashboard') }}">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            @if ($vs->user?->can('ProjectPolicy@hasAccess'))
                <li>
                    <a class="{{ ($vs->isPage)('page.projects') }}" href="{{ route('projects.dashboard') }}">
                        <i class="fa fa-clipboard-list"></i>
                    </a>
                </li>
            @endif
            <li>
                <a class="{{ ($vs->isPage)('page.journals') }}" href="{{ route('journals.dashboard') }}">
                    <i class="fa fa-sticky-note"></i>
                </a>
            </li>
            <li>
                <a class="{{ ($vs->isPage)('page.time-logs') }}" href="{{ route('time-logs.calendar') }}">
                    <i class="fa fa-clock"></i>
                </a>
            </li>
            <li>
                <a class="{{ ($vs->isPage)('page.accounts') }}" href="{{ route('accounts') }}">
                    <i class="fa fa-lock"></i>
                </a>
            </li>
            <li>
                <a class="{{ ($vs->isPage)('page.store')}} " href="{{ route('store.products') }}">
                    <i class="fa fa-shopping-basket"></i>
                </a>
            </li>
            <li>
                <a class="{{ ($vs->isPage)('system.changelogs') }}" href="{{ route('system.changelogs') }}">
                    <i class="fa fa-clipboard"></i>
                </a>
            </li>
            <li class="theme-wrapper">
                <a class="toggle-theme">
                    <span class="light-theme">☀️</span>
                    <span class="active dark-theme">🌙</span>
                </a>
            </li>
            <li>
                <div class="user-wrapper">
                    <a class="user {{ ($vs->isPage)('page.user') }}" data-user_id="{{ $vs->user?->id }}">
                        <img src="{{ $vs->user?->getProfileImage() }}"
                             alt="{{ $vs->user?->getFullName() }}"
                        />
                    </a>
                    <div class="user-dropdown">
                        <a href="{{ route('users.user', $vs->user?->id) }}">
                            <i class="fa fa-user"></i>My Account
                        </a>
                        <a href="{{ route('projects.tasks.create') }}"
                           create-task
                        ><i class="fa fa-tasks"></i>Create Task</a>
                        <a href="{{ route('projects.create.view') }}"
                           create-project
                        ><i class="fa fa-sticky-note"></i>Create Project</a>
                        <a href="{{ route('system.store') }}"><i class="fa fa-database"></i>Store All</a>
                        <a href="{{ route('user.logout') }}"><i class="fa fa-sign-out-alt"></i>Logout</a>
                    </div>
                </div>
            </li>
        </ul>
        @if ($vs->hasSidebar)
            <a class="close-sidebar">
                <i class="fa fa-chevron-right"></i>
            </a>
        @endif
    </header>
    @if ($vs->hasSidebar)
        <div class="header-sidebar">
            <div>
                @yield('sidebar')
            </div>
        </div>
    @endif
@endif