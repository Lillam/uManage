@extends('template.master')
@section('body')
    <div class="login_wrapper">
        <div class="uk-grid uk-grid-collapse" uk-grid>
            {{-- Login wrapper for the title of the application and social media constructs... --}}
            <div class="uk-width-3-5@l uk-width-1-2@m uk-flex uk-flex-middle uk-flex-center login_title">
                <div class="uk-grid uk-grid-collapse uk-flex uk-width-1-1" uk-grid>
                    {{-- Social media platforms wrapper (all social platforms will be located and identified here --}}
                    <div class="uk-width-auto uk-flex uk-flex-middle social_wrapper">
                        <div>
                            @foreach (config('social') as $social)
                                <a class="social_icon">
                                    <i class="{{ $social['icon'] }}"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="uk-width-expand uk-flex uk-flex-middle uk-flex-center">
                        <h2>{{ env('APP_NAME') }}</h2>
                    </div>
                </div>
                {{-- Background image for the login screen... --}}
                {{--<div class="login_title_background"
                     style="background-image: url({{ asset('images/header_background.jpg') }});">
                </div>--}}
            </div>
            <div class="uk-width-2-5@l uk-width-1-2@m login_form">
                <div class="login_form_inner uk-flex uk-flex-middle uk-flex-center">
                    <form class="" method="POST" action="{{ route('user.login') }}">
                        @csrf
                        <div class="input_wrapper {{ $errors->has('email') ? 'error' : '' }}">
                            <input id="email" type="email"
                                   name="email"
                                   class="uk-width-1-1"
                                   autocomplete="off"
                                   value="{{ old('email') }}" placeholder="Enter Email"
                            />
                            <label for="email" class="placeholder">Enter Email</label>
                            @if ($errors->has('email'))
                                <p>{{ $errors->first('email') }}</p>
                            @endif
                        </div>
                        <div class="input_wrapper {{ $errors->has('password') ? 'error' : '' }}">
                            <input id="password" type="password"
                                   name="password"
                                   class="uk-width-1-1"
                                   autocomplete="off" placeholder="Enter Password"
                            />
                            <label for="password" class="placeholder">Enter Password</label>
                            @if ($errors->has('password'))
                                <p>{{ $errors->first('password') }}</p>
                            @endif
                        </div>
                        <button class="uk-button uk-button-primary">Login</button>
                        <p>
                            <a href="">Forgot password</a>
                            <a href="">Register an account</a>
                        </p>
                    </form>

            </div>
        </div>
    </div>
@endsection