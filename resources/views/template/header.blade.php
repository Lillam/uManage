@if ($vs->has_header)
    <header>
        <ul>
            <li class="app-logo uk-height-expand"><a href="{{ action('User\UserController@_viewUserDashboardGet') }}">U</a></li>
            <li><a href="{{ action('System\SystemModuleController@_viewSystemModuleDashboardGet') }}"><i class="fa fa-shapes"></i></a></li>
            <li>
                <div class="user-wrapper">
                    <a class="user {{ ($vs->is_page)('page.user') }}" data-user_id="{{ $vs->user?->id }}">
                        <img src="{{ $vs->user?->getProfileImage() }}" />
                    </a>
                    <div class="user-dropdown">
                        <a href="{{ action('User\UserController@_viewUserGet', $vs->user?->id) }}"><i class="fa fa-user"></i>My Account</a>
                        <a href="{{ action('Task\TaskController@_ajaxViewCreateTaskGet') }}" create-task><i class="fa fa-tasks"></i>Create Task</a>
                        <a href="{{ action('Project\ProjectController@_ajaxViewCreateProjectGet') }}" create-project><i class="fa fa-sticky-note"></i>Create Project</a>
                        <a href="{{ action('System\SystemController@_storeAllModulesLocally') }}"><i class="fa fa-database"></i>Store All</a>
                        <a href="{{ action('User\UserController@_userLogout') }}"><i class="fa fa-sign-out-alt"></i>Logout</a>
                    </div>
                </div>
            </div>
            </li>
        </ul>
    </header>
    @if ($vs->has_sidebar)
        <div class="header-sidebar">
            <div>
                <h2>Hi <span class="hide-at-800">{{ $vs->user?->getFullName() }}</span></h2>
                <hr />
                {{-- Dashboard --}}
                <a class="{{ ($vs->is_page)('page.dashboard') }}"
                   href="{{ action('User\UserController@_viewUserDashboardGet') }}">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <hr />
                {{-- Projects --}}
                @if ($vs->user?->can('ProjectPolicy@hasAccess'))
                    <a class="{{ ($vs->is_page)('page.projects') }}"
                       href="{{ action('Project\ProjectController@_viewProjectsGet') }}">
                        <i class="fa fa-sticky-note"></i>
                        <span>Projects</span>
                    </a>
                @endif
                {{-- Tasks --}}
                <a class="{{ ($vs->is_page)('page.tasks') }}"
                   href="{{ action('Task\TaskController@_viewTasksGet') }}">
                    <i class="fa fa-tasks"></i>
                    <span>Tasks</span>
                </a>
                {{-- Task Activity --}}
                <a class="{{ ($vs->is_page)('page.tasks.activity') }}"
                   href="{{ action('Task\TaskLogController@_viewTaskLogActivityGet') }}">
                    <i class="fa fa-history"></i>
                    <span>Task Activity</span>
                </a>
                {{-- Joournals --}}
                <hr />
                <a class="{{ ($vs->is_page)('page.journals') }}"
                   href="{{ action('Journal\JournalController@_viewJournalsGet') }}">
                    <i class="fa fa-calendar"></i>
                    <span>Journals</span>
                </a>
                <a class="{{ ($vs->is_page)('page.journals.dreams') }}"
                   href="{{ action('Journal\JournalDreamController@_viewJournalDreamsGet') }}">
                    <i class="fa fa-moon"></i>
                    <span>Dream Journal</span>
                </a>
                <a class="{{ ($vs->is_page)('page.journals.finances') }}"
                   href="{{ action('Journal\JournalFinanceController@_viewJournalFinancesGet') }}">
                    <i class="fa fa-dollar-sign"></i>
                    <span>Finances Journal</span>
                </a>
                {{-- Time Logging --}}
                <hr />
                <a class="{{ ($vs->is_page)('page.timelogs') }}"
                   href="{{ action('Timelog\TimelogController@_viewTimelogCalendarGet') }}">
                    <i class="fa fa-clock"></i>
                    <span>Timelogging</span>
                </a>
                {{-- Accounts --}}
                <hr />
                <a class="{{ ($vs->is_page)('page.accounts') }}"
                   href="{{ action('Account\AccountController@_viewAccountsGet') }}">
                    <i class="fa fa-lock"></i>
                    <span>Accounts</span>
                </a>
                {{-- Store --}}
                <hr />
                <a class="{{ ($vs->is_page)('page.store') ? 'active' : '' }}"
                   href="{{ action('Store\StoreProductController@_viewStoreProductsGet') }}">
                    <i class="fa fa-shopping-basket"></i>
                    <span>Store</span>
                </a>
                <hr />
                <a class="{{ ($vs->is_page)('system.changelogs') ? 'active' : '' }}"
                   href="{{ action('System\SystemChangelogController@_viewSystemChangelogsGet') }}">
                    <i class="fa fa-clipboard"></i>
                    <span>System Changelogs</span>
                </a>
                {{-- Extra Links --}}
                @yield('sidebar')
            </div>
        </div>
    @endif
@endif