@extends('template.master')
@section('css')
    {!! ($vs->css)('views/user/view_users') !!}
@endsection
@section('body')
    <div class="navigation">
        <div class="uk-flex">
            <div class="uk-width-expand uk-flex uk-flex-middle">
                <span>Users</span>
            </div>
            <div class="uk-width-auto uk-border-left">
                <a class="tasks_navigation_right uk-button uk-button-icon"><i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="users">
        @foreach ($users as $user)
            <div class="user_row">
                {!! UserPrinter::userBadge($user) !!} {!! UserPrinter::linkedUser($user) !!}
            </div>
        @endforeach
    </div>
@endsection