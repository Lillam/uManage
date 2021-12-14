@foreach($accounts as $account)
    <div class="account_item_wrapper" data-account_id="{{ $account->id }}">
        <div class="uk-grid uk-grid-small" uk-grid>
            <div class="account_item_platform account_item_account uk-width-expand uk-flex uk-flex-middle">
                <p>{{ $account->application }}<br /><span>{{ $account->account }}</span></p>
            </div>
            <div class="account_item_options uk-width-auto uk-flex uk-flex-middle uk-flex-last@l">
                <a class="view_account_password"><i class="fa fa-lock"></i></a>
                <a class="edit_account_password"><i class="fa fa-edit"></i></a>
                <a class="delete_account_password"><i class="fa fa-trash"></i></a>
            </div>
            <div class="account_item_password uk-flex uk-flex-middle uk-width-expand@l uk-width-1-1">
                <p>Password: <br /><span>{{ $account->getShortPassword() }}</span></p>
            </div>
        </div>
    </div>
@endforeach