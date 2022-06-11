@extends('template.master')
@section('css')
    {!! ($vs->css)('views/user/view_user') !!}
    {!! ($vs->css)('views/task/view_task_list') !!}
    {!! ($vs->css)('views/project/view_projects') !!}
    {!! ($vs->css)('views/system_modules/view_system_modules') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/user/view_user') !!}
    {!! ($vs->js)('views/project/view_projects') !!}
    {!! ($vs->js)('views/task/view_tasks') !!}
@endsection
@section('title-block')
    <p>Profile</p>
@endsection
@section('sidebar')
    <h2>{{ $user->getFullName() }}</h2>
@endsection
@section('body')
    {{-- The User's Banner Strip --}}
    <div class="section no-border-top">
        {{-- User Profile Image --}}
        <div class="user_profile_wrapper">
            <div class="uk-grid uk-grid-small" uk-grid>
                {{-- User Image --}}
                <div class="uk-width-1-3@m">
                    <h2 class="section_title">Me</h2>
                    <div class="box">
                        <img class="profile_image" src="{{ $user->getProfileImage() }}" />
                    </div>
                </div>
                {{-- User Profile Name, and Profile Details --}}
                <div class="uk-width-1-3@m">
                    <h2 class="section_title">About</h2>
                    <div class="box">
                        <div class="uk-flex form_element_row">
                            <div class="uk-width-auto uk-flex uk-flex-middle">
                                <div><i class="fa fa-question"></i></div>
                            </div>
                            <div class="uk-width-expand">
                                <div>
                                    @can('UserPolicy@editUser', $user)
                                        <input type="text" placeholder="Your First Name" value="{{ $user->first_name }}" />
                                    @else
                                        <div class="placeholder">{{ $user->first_name }}</div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="uk-flex form_element_row">
                            <div class="uk-width-auto uk-flex uk-flex-middle">
                                <div><i class="fa fa-question"></i></div>
                            </div>
                            <div class="uk-width-expand">
                                <div>
                                    @can('UserPolicy@editUser', $user)
                                        <input type="text" placeholder="Your Last Name" value="{{ $user->last_name }}" />
                                    @else
                                        <div class="placeholder">{{ $user->last_name }}</div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="uk-flex form_element_row">
                            <div class="uk-width-auto uk-flex uk-flex-middle">
                                <div><i class="fa fa-briefcase"></i></div>
                            </div>
                            <div class="uk-width-expand">
                                <div>
                                    @can('UserPolicy@editUser', $user)
                                        <input type="text" placeholder="Your Job Title" />
                                    @else
                                        <div class="placeholder">Your Job Title</div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="uk-flex form_element_row">
                            <div class="uk-width-auto uk-flex uk-flex-middle">
                                <div><i class="fa fa-briefcase"></i></div>
                            </div>
                            <div class="uk-width-expand">
                                @can('UserPolicy@editUser', $user)
                                    <input type="text" placeholder="Your Department" />
                                @else
                                    <div class="placeholder">Your Department</div>
                                @endcan
                            </div>
                        </div>
                        <div class="uk-flex form_element_row">
                            <div class="uk-width-auto uk-flex uk-flex-middle">
                                <div><i class="fa fa-address-book"></i></div>
                            </div>
                            <div class="uk-width-expand">
                                @can('UserPolicy@editUser', $user)
                                    <input type="text" placeholder="Your Address" />
                                @else
                                    <div class="placeholder">Your Address</div>
                                @endcan
                            </div>
                        </div>
                        <div class="uk-flex form_element_row">
                            <div class="uk-width-auto uk-flex uk-flex-middle">
                                <div><i class="fa fa-clock-o"></i></div>
                            </div>
                            <div class="uk-width-expand">
                                @can('UserPolicy@editUser', $user)
                                    <input type="text" placeholder="Your Timezone" />
                                @else
                                    <div class="placeholder">Your Timezone</div>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <h2 class="section_title uk-margin-top">Contact</h2>
                    <div class="box">
                        <div class="uk-flex form_element_row">
                            <div class="uk-width-auto uk-flex uk-flex-middle">
                                <div><i class="fa fa-envelope"></i></div>
                            </div>
                            <div class="uk-width-expand">
                                @can('UserPolicy@editUser', $user)
                                    <input type="text" placeholder="Your Email" value="{{ $user->email }}" />
                                @else
                                    <div class="placeholder">{{ $user->email }}</div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($vs->user->can('ProjectPolicy@hasAccess'))
        <div class="section">
            <h2>{{ $user->getFullName() }}'s Projects</h2>
            <div class="projects"
                 data-view_projects_url="{{ action('Project\ProjectController@_ajaxViewProjectsGet') }}"
                 data-view_mode="slider">
            </div>
        </div>
    @endif
    @if ($users_i_work_with->isNotEmpty())
        <div class="section">
            <h2>{{ $user->first_name }} works with</h2>
            <div class="uk-grid uk-grid-small" uk-grid>
                @foreach ($users_i_work_with as $user_i_work_with)
                    <div class="uk-width-1-5@xl uk-width-1-4@m uk-width-1-2">
                        <div class="user_card">
                            <a href="{{ $user_i_work_with->getUrl() }}">
                                <div class="uk-flex uk-flex-middle uk-flex-center">
                                    <img src="{{ $user_i_work_with->getProfileImage() }}" />
                                </div>
                                <h2>{{ $user_i_work_with->getFullName() }}</h2>
                                <span>Job Description</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection