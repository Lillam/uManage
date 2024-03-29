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
                        <img class="profile_image"
                             alt="{{ $user->getFullName() }}"
                             src="{{ $user->getProfileImage() }}"
                        />
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
                                        <label for="first_name" class="uk-hidden">First Name</label>
                                        <input id="first_name"
                                               type="text"
                                               placeholder="Your First Name"
                                               value="{{ $user->first_name }}" />
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
                                        <label for="last_name" class="uk-hidden">Last Name</label>
                                        <input id="last_name"
                                               type="text"
                                               placeholder="Your Last Name"
                                               value="{{ $user->last_name }}" />
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
                                        <label for="job_title" class="uk-hidden">Job Title</label>
                                        <input id="job_title" type="text" placeholder="Your Job Title" />
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
                                    <label for="department" class="uk-hidden">Department</label>
                                    <input id="department" type="text" placeholder="Your Department" />
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
                                    <label for="address" class="uk-hidden">Address</label>
                                    <input id="address" type="text" placeholder="Your Address" />
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
                                    <label for="timezone" class="uk-hidden">Timezone</label>
                                    <input id="timezone" type="text" placeholder="Your Timezone" />
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
                                    <label for="user_email" class="uk-hidden">Email</label>
                                    <input id="user_email"
                                           type="text"
                                           placeholder="Your Email"
                                           value="{{ $user->email }}" />
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
                 data-view_projects_url="{{ route('projects.list.ajax') }}"
                 data-view_mode="slider">
            </div>
        </div>
    @endif
    @if ($usersIWorkWith->isNotEmpty())
        <div class="section">
            <h2>{{ $user->first_name }} works with</h2>
            <div class="uk-grid uk-grid-small" uk-grid>
                @foreach ($usersIWorkWith as $UserIWorkWith)
                    <div class="uk-width-1-5@xl uk-width-1-4@m uk-width-1-2">
                        <div class="user_card">
                            <a href="{{ $UserIWorkWith->getUrl() }}">
                                <div class="uk-flex uk-flex-middle uk-flex-center">
                                    <img alt="{{ $UserIWorkWith->getFullName() }}"
                                         src="{{ $UserIWorkWith->getProfileImage() }}"
                                    />
                                </div>
                                <h2>{{ $UserIWorkWith->getFullName() }}</h2>
                                <span>Job Description</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection