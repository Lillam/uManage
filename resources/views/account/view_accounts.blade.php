@extends('template.master')
@section('css')
    {!! ($vs->css)('views/account/view_accounts') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/account/view_accounts') !!}
@endsection
@section('sidebar')
    @include('library.account.account_sidebar')
@endsection
@section('title-block')
    <div class="uk-width-expand">
        <p>Account Management</p>
    </div>
    <div class="uk-flex accounts_title navigation">
        <a class="accounts_info uk-button uk-icon-button"><i class="fa fa-info-circle"></i></a>
    </div>
@endsection
@section('body')
    <div class="accounts_wrapper">
        <div class="accounts"
             data-view_accounts_url="{{ route('accounts.ajax') }}"
             data-make_accounts_url="{{ route('accounts.create.ajax') }}"
             data-delete_acocunts_url="{{ route('accounts.delete.ajax') }}"
             data-view_accounts_password_url="{{ route('accounts.password.view.ajax') }}">
        </div>
        {{-- Wrapper for making new accounts. --}}
        <div class="accounts_sidebar">
            <div class="uk-width-1-1">
                <label for="account_application" class="accounts_label">Application</label>
                <input id="account_application" type="text" class="uk-input account_application" />
            </div>
            <div class="uk-width-1-1 uk-margin-small-top">
                <label for="account_account" class="accounts_label">Account Username/Email</label>
                <input id="account_account" type="text" class="uk-input account_account" />
            </div>
            <div class="uk-width-1-1 uk-margin-small-top">
                <label for="account_password" class="accounts_label">Password</label>
                <input id="account_password" type="text" class="uk-input account_password" />
            </div>
            <div class="uk-width-1-1 uk-margin-small-top uk-text-right">
                <a class="save_account uk-button uk-button-small uk-button-primary"><i class="fa fa-plus"></i> Add</a>
            </div>
        </div>
    </div>
@endsection