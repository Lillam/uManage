@if ($projects->isNotEmpty())
    <div class="app-projects-wrapper">
        @if ($view_mode === 'slider')
            <div uk-slider="sets: true">
                <ul class="uk-slider-items app-project-grid uk-child-width-1-3@xl uk-child-width-1-2@m uk-grid-small">
                    @foreach ($projects as $project_key => $project)
                        <li>
                            <a class="app-project project" href="{{ $project->getUrl() }}">
                                <div class="project_title_wrapper">
                                    {!! \App\Printers\User\UserPrinter::userBadges(
                                        $project->user_contributors->map(function ($user_contributor) {
                                            return $user_contributor->user;
                                        }), false, $project->getColor()
                                    ) !!}
                                    <h2 class="project_title"><span>Project</span> {{ $project->name }}</h2>
                                    <p><span>Tasks:</span>#{{ $project->getTotalTasks() }}</p>
                                </div>
                                <div class="progress_wrapper">
                                    <div class="progress">
                                        <div class="progress_percent completed"
                                             style="width: {{ $project->getTaskCompletedPercentage() }};">
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <span class="app-project-code"
                                  style="background-color: {{ $project->getColor() }}">{{ $project->code }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="uk-flex uk-grid uk-grid-collapse app-projects-navigation">
                    <div class="uk-width-expand uk-flex uk-flex-center uk-flex-middle">
                        <ul class="uk-slider-nav uk-dotnav"></ul>
                    </div>
                    <div class="uk-width-auto">
                        <a href="" class="uk-button uk-button-primary">View All Projects</a>
                    </div>

                </div>
            </div>
        @endif
        @if ($view_mode !== 'slider')
            <div class="uk-grid uk-grid-small app-project-grid" uk-grid>
                @foreach ($projects as $project_key => $project)
                    <div class="uk-width-1-3@xl uk-width-1-2@m uk-width-1-1">
                        <a class="app-project project" href="{{ $project->getUrl() }}">
                            <div class="project_title_wrapper">
                                {!! \App\Printers\User\UserPrinter::userBadges(
                                    $project->user_contributors->map(function ($user_contributor) {
                                        return $user_contributor->user;
                                    }), false, $project->getColor()
                                ) !!}
                                <h2 class="project_title"><span>Project</span> {{ $project->name }}</h2>
                                <p><span>Tasks:</span>#{{ $project->getTotalTasks() }}</p>
                            </div>
                            <div class="progress_wrapper">
                                <div class="progress">
                                    <div class="progress_percent completed"
                                         style="width: {{ $project->getTaskCompletedPercentage() }};">
                                    </div>
                                </div>
                            </div>
                        </a>
                        <span class="app-project-code"
                              style="background-color: {{ $project->getColor() }}">{{ $project->code }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endif

{{--<div class="uk-width-1-3@xl uk-width-1-2@m uk-width-1-1">--}}
{{--    <a class="project" href="{{ $project->getUrl() }}">--}}
{{--        <div class="uk-flex project_title_wrapper">--}}
{{--            <div class="uk-width-expand">--}}
{{--                <h2 class="project_title"><span>Project</span> {{ $project->name }}</h2>--}}
{{--            </div>--}}
{{--            <div class="uk-width-auto">--}}
{{--                {!! \App\Printers\User\UserPrinter::userBadges(--}}
{{--                    $project->user_contributors->map(function ($user_contributor) {--}}
{{--                        return $user_contributor->user;--}}
{{--                    }), false, $project->getColor()--}}
{{--                ) !!}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="project_stats uk-flex" style="background-color: {{ $project->getColor() }}">--}}
{{--            --}}{{-- in To do --}}
{{--            <div class="uk-width-expand">--}}
{{--                <span class="project_total">{{ $project->project_setting->tasks_in_todo }}</span>--}}
{{--                <span>To Do</span>--}}
{{--            </div>--}}
{{--            --}}{{-- In Progress --}}
{{--            <div class="uk-width-expand">--}}
{{--                <span class="project_total">{{ $project->project_setting->tasks_in_progress }}</span>--}}
{{--                <span>In Progress</span>--}}
{{--            </div>--}}
{{--            --}}{{-- In Completed --}}
{{--            <div class="uk-width-expand">--}}
{{--                <span class="project_total">{{ $project->project_setting->tasks_in_completed }}</span>--}}
{{--                <span>Completed</span>--}}
{{--            </div>--}}
{{--            --}}{{-- In Archieved --}}
{{--            <div class="uk-width-expand uk-visible@s">--}}
{{--                <span class="project_total">{{ $project->project_setting->tasks_total }}</span>--}}
{{--                <span>Total</span>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="progress_wrapper">--}}
{{--            <div class="progress">--}}
{{--                <div class="progress_percent completed"--}}
{{--                     style="width: {{ $project->getTaskCompletedPercentage() }};">--}}
{{--                </div>--}}
{{--                <div class="progress_percent in_progress"--}}
{{--                     style="width: {{ $project->getTaskProgressPercentage() }};">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </a>--}}
{{--</div>--}}