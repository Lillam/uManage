$(() => {
    let $body = $('body');

    $body.on('click', '.add_new_project a', function (event) {
        event.preventDefault();
        event.stopPropagation();
        $('#add_sidebar_button').click();
        if (! $('.sidebar_add_new_project').hasClass('active')) {
            $('.sidebar_add_new_project').click();
        }
    });
});