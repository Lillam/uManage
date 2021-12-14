@extends('template.master')
@section('css')
    {!! ($vs->css)('views/account/view_accounts') !!}
@endsection
@section('js')
    {!! ($vs->js)('views/account/view_accounts') !!}
@endsection
@section('body')
    <div class="accounts_wrapper">
        <div class="accounts_title uk-flex">
            <div class="uk-width-expand uk-flex uk-flex-middle">
                <h2>Account Management</h2>
            </div>
            <div class="uk-width-auto">
                <a class="accounts_info uk-button uk-icon-button"><i class="fa fa-info-circle"></i></a>
            </div>
        </div>
        <div class="accounts"
             data-view_accounts_url="{{ action('Account\AccountController@_ajaxViewAccountsGet') }}"
             data-make_accounts_url="{{ action('Account\AccountController@_ajaxMakeAccountsPost') }}"
             data-delete_acocunts_url="{{ action('Account\AccountController@_ajaxDeleteAccountsGet') }}"
             data-view_accounts_password_url="{{ action('Account\AccountController@_ajaxViewAccountsPasswordGet') }}">
        </div>
        {{-- Wrapper for making new accounts. --}}
        <div class="accounts_sidebar">
            <div class="uk-width-1-1">
                <label class="accounts_label">Application</label>
                <input type="text" class="uk-input account_application" />
            </div>
            <div class="uk-width-1-1 uk-margin-small-top">
                <label class="accounts_label">Account Username/Email</label>
                <input type="text" class="uk-input account_account" />
            </div>
            <div class="uk-width-1-1 uk-margin-small-top">
                <label class="accounts_label">Password</label>
                <input type="text" class="uk-input account_password" />
            </div>
            <div class="uk-width-1-1 uk-margin-small-top uk-text-right">
                <a class="save_account uk-button uk-button-small uk-button-primary"><i class="fa fa-plus"></i> Add</a>
            </div>
        </div>
    </div>
@endsection