@extends('template.master')
@section('sidebar')
    @include('library.account.account_sidebar')
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p><b>{{ $account->application }}</b> @ {{ $account->account }}</p>
    </div>
@endsection
@section('body')
    <div class="">
        <div class="account-viewed">
            Viewed: {{ $account->access->access_count }} times
        </div>
        <div class="credentials">
            <p>Password: {{ $password }}</p>
            <p>Recovery Code: {{ $twoFactorRecovery ?? "Not Set" }}</p>
        </div>
    </div>
@endsection
