@extends('template.master')
@section('sidebar')
    @include('library.account.account_sidebar')
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p><b>{{ $account->application }}</b> @ {{ $account->account }}</p>
    </div>
@endsection