@foreach($accounts as $account)
    <div class="account_item_wrapper" data-account_id="{{ $account->id }}">
        <a href="{{ route('accounts.account', $account->id) }}">
            <div class="uk-grid uk-grid-small account_item_platform account_item_account" uk-grid>
                <div class="uk-width-expand uk-flex uk-flex-middle">
                    <p>{{ $account->application }}</p>
                </div>
                <div class="uk-width-auto">
                    <p><span>{{ $account->account }}</span></p>
                </div>
    {{--            <div class="account_item_options uk-width-auto uk-flex uk-flex-middle uk-flex-last@l">--}}
    {{--                <a class="view_account_password"><i class="fa fa-lock"></i></a>--}}
    {{--                <a class="edit_account_password"><i class="fa fa-edit"></i></a>--}}
    {{--                <a class="delete_account_password"><i class="fa fa-trash"></i></a>--}}
    {{--            </div>--}}
    {{--            <div class="account_item_password uk-flex uk-flex-middle uk-width-expand@l uk-width-1-1">--}}
    {{--                <p>Password: <span>{{ $account->getShortPassword() }}</span></p>--}}
    {{--            </div>--}}
            </div>
        </a>
    </div>
@endforeach