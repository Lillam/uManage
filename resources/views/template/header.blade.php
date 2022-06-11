@if ($vs->has_header)
    <header>
        <ul>
            <li class="app-logo uk-height-expand"><a href="{{ route('user.dashboard') }}">u<span>M</span></a></li>
            <li>
                <a class="{{ ($vs->is_page)('page.dashboard') }}"
                   href="{{ action('User\UserController@_viewUserDashboardGet') }}"
                ><i class="fa fa-home"></i></a>
            </li>
            @if ($vs->user?->can('ProjectPolicy@hasAccess'))
                <li>
                    <a class="{{ ($vs->is_page)('page.projects') }}"
                       href="{{ route('projects.dashboard') }}"
                    ><i class="fa fa-sticky-note"></i></a>
                </li>
            @endif
            <li>
                <a class="{{ ($vs->is_page)('page.journals') }}"
                   href="{{ route('journals.dashboard') }}"><i class="fa fa-calendar"></i></a>
            </li>
            <li>
                <a class="{{ ($vs->is_page)('page.timelogs') }}"
                   href="{{ action('Timelog\TimelogController@_viewTimelogCalendarGet') }}"
                ><i class="fa fa-clock"></i></a>
            </li>
            <li>
                <a class="{{ ($vs->is_page)('page.accounts') }}"
                   href="{{ action('Account\AccountController@_viewAccountsGet') }}"
                ><i class="fa fa-lock"></i></a>
            </li>
            <li>
                <a class="{{ ($vs->is_page)('page.store') ? 'active' : '' }}"
                   href="{{ action('Store\StoreProductController@_viewStoreProductsGet') }}"
                ><i class="fa fa-shopping-basket"></i></a>
            </li>
            <li>
                <a class="{{ ($vs->is_page)('system.changelogs') ? 'active' : '' }}"
                   href="{{ action('System\SystemChangelogController@_viewSystemChangelogsGet') }}"
                ><i class="fa fa-clipboard"></i></a>
            </li>
            <li>
                <div class="user-wrapper">
                    <a class="user {{ ($vs->is_page)('page.user') }}" data-user_id="{{ $vs->user?->id }}">
                        <img src="{{ $vs->user?->getProfileImage() }}" alt="{{ $vs->user?->getFullName() }}"/>
                    </a>
                    <div class="user-dropdown">
                        <a href="{{ action('User\UserController@_viewUserGet', $vs->user?->id) }}">
                            <i class="fa fa-user"></i>My Account
                        </a>
                        <a href="{{ action('Task\TaskController@_ajaxViewCreateTaskGet') }}" create-task>
                            <i class="fa fa-tasks"></i>Create Task
                        </a>
                        <a href="{{ action('Project\ProjectController@_ajaxViewCreateProjectGet') }}" create-project>
                            <i class="fa fa-sticky-note"></i>Create Project
                        </a>
                        <a href="{{ route('system.store') }}"><i class="fa fa-database"></i>Store All</a>
                        <a href="{{ route('user.logout') }}"><i class="fa fa-sign-out-alt"></i>Logout</a>
                    </div>
                </div>
            </li>
        </ul>
        @if ($vs->has_sidebar)
            <a class="close-sidebar"><i class="fa fa-chevron-right"></i></a>
        @endif
    </header>
    @if ($vs->has_sidebar)
        <div class="header-sidebar">
            <div>
                @yield('sidebar')
            </div>
        </div>
    @endif
@endif