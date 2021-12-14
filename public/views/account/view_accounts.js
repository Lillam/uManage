$(() => {
    // pre define body, so we can append a bunch of things to this element...
    let $body = $('body');

    // if the user clicks anywhere on the body, and the sidebar is open (this will only happen on mobile) but it will
    // remove the open class from the sidebar, so that the sidebar will be hidden on mobile again; so that the user can
    // interact with the page.
    $body.on('click', function (event) {
        $('.accounts_wrapper').removeClass('accounts_sidebar_open');
    });

    // if the user clicks anywhere in the sidebar, we are going to want to prevent the above from closing the sidebar,
    // as the above method is closing the sidebar if the user clicks anywhere around the body.
    $body.on('click', '.accounts_sidebar', function (event) {
        event.stopPropagation();
    });

    // when the user clicks on the accounts info button. depending on the screensize, we are either going to hide, or
    // open the sidebar, this will happen on both screensizes, however there are some styles in place, which will
    // either say whether or not the position is fixed over, or pushing the content to the side... only if the
    // screen size is above 991... then we are going to push it in, otherwise we are going to overlay.
    $body.on('click', '.accounts_info', function (event) {
        event.stopPropagation();

        let $accounts_wrapper = $('.accounts_wrapper');

        if (window.innerWidth <= 991) {
            $accounts_wrapper.removeClass('accounts_sidebar_closed');
            $accounts_wrapper.toggleClass('accounts_sidebar_open');
        }

        if (window.innerWidth > 991) {
            $accounts_wrapper.toggleClass('accounts_sidebar_closed');
            $accounts_wrapper.removeClass('accounts_sidebar_open');
        }
    });

    // when the user opts to click on save account, we are just going to fire off to the make_account method as this
    // will be taking care of finding all the information that will be needed in order for making an account entry.
    // once this is done, it will simply reload the accounts that are against the authenticated user.
    $body.on('click', '.save_account', function (event) {
        make_account();
    });

    $body.on('click', '.view_account_password', function (event) {
        var $this = $(this),
            show = true,
            account_id = $this.closest('.account_item_wrapper').data('account_id');

        if ($this.hasClass('active')) {
            show = false;
        }

        view_account_password(account_id, show);

        $this.toggleClass('active');
    });

    // when the user clicks on a delete account password button, then this is going to find the account row for this
    // entry, pass in the id to delete account method, which will fire off to the server, in order to delete this
    // particular row, as the user is opting to get rid of it.
    $body.on('click', '.delete_account_password', function (event) {
        let $this = $(this),
            account_id = $this.closest('.account_item_wrapper').data('account_id');

        delete_account(account_id);
    });

    // initiate the page when it loads.
    view_accounts();
});

/**
* This method is utilised for viewing the accounts in the system, upon the loading of this page in particular; we will
* be showing the user all the accounts that they have against their name.
*
* @return void
*/
var view_accounts = function () {
    let $accounts = $('.accounts'),
        view_accounts_url = $accounts.data('view_accounts_url');

    $.ajax({
        method: 'get',
        url: view_accounts_url,
        data: {},
        success: function (data) {
            $accounts.html(data);
        }
    });
};

/**
* This method will be called when the user clicks on the "make_account" button... when this happens the javascript will
* check for the accounts_sidebar for three inputs:
* .account_account (the account that we're making an account for)
* .account_application (the application of the account that we're making)
* .account_password (the password of the application that we're making).
* upon the creation of this account, the system will connect back to the database referring to (view_accounts()) which
* will update the user interface with all the new accounts (aka, updating the view with the new view)
*
* @return void
*/
var make_account = function () {
    let $accounts = $('.accounts'),
        $accounts_sidebar = $('.accounts_sidebar'),
        make_accounts_url = $accounts.data('make_accounts_url');

    $.ajax({
        method: 'post',
        url: make_accounts_url,
        data: {
            account: $accounts_sidebar.find('.account_account').val(),
            application: $accounts_sidebar.find('.account_application').val(),
            password: $accounts_sidebar.find('.account_password').val()
        },
        success: function (data) {
            view_accounts();
            $accounts_sidebar.find('input').val('');
        }
    });
};

/**
* This method will be utilised for deleting an account (this is handled by ajax) there's no real need for us to refresh
* this page in particular, all we need to do is ajax to the server, check if we're able to delete the account in
* question, if we are then we can just delete it and return success... otherwise, we return an error message and
* display that error there and then so that the user can have instant feedback without the need for refreshing.
*
* @param account_id
*/
var delete_account = function (account_id) {
    let $accounts = $('.accounts'),
        delete_accounts_url = $accounts.data('delete_acocunts_url');

    $.ajax({
        method: 'get',
        url: delete_accounts_url,
        data: {
            account_id: account_id
        },
        success: function (data) {
            view_accounts();
        }
    });
};

/**
* This method is utilised for showing the users password; this will just connect to the server, get the encryption key
* and spit the password to the account back to the user on the frontend in readable english rather than encrypted
* format...
*
* @param account_id
* @param show
*/
var view_account_password = function (account_id, show = false) {
    let $accounts = $('.accounts'),
        view_account_password_url = $accounts.data('view_accounts_password_url');
    $.ajax({
        method: 'get',
        url: view_account_password_url,
        data: {
            account_id: account_id,
            show: show
        },
        success: function (data) {
            let $account_row_item = $('.accounts_wrapper');

            $account_row_item.find('.account_item_wrapper[data-account_id="' + data.account_id + '"]')
                .find('.account_item_password')
                .html("<p>Password: <br /><span>" + data.password + "</span></p>");
        }
    });
};