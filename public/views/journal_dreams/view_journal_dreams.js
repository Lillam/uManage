$(() => {
    // defining the body to a variable, so that we are going to be able to keep applying methods onto the body, rather
    // than applying methods to the designated methods, which are going to be modified and or injected in via ajax.
    // default place of event handling.
    let $body = $('body');

    // when the user clicks on the left or right calendar journal dreams button then we are going to reload the
    // journal dreams in the system; and we are going to re-call the method passing in a direction, the direction will
    // either be left or right, if left we are going to return a date, which is minus one month or plus one month
    // depending on the direction of which we are going.
    $body.on('click', '.journal_dreams_calendar_left, .journal_dreams_calendar_right', function (event) {
        event.preventDefault();
        let $this = $(this),
            direction = $this.hasClass('journal_dreams_calendar_left') ? 'left' : 'right';
        view_journal_dreams(direction);
    });

    // initially load the journal dreams.and having this initially load the page with the necessary data... this will
    // just populate the page with a variety of dates, and whether or not the values have things against it will
    // depend on the database entries for this month.
    view_journal_dreams();
});

/**
* This method will be for populating the journal dreams on the page, this will by design, assign information to the page
*inside of the journal_dreams identified div wrapper; when this method completes, the element with the necessary id
* will be populated with data which will be returned by the server. this method takes a direction, and depending on
* the direction, depends on what updates, if the direction is left, we are subtracting a month, and getting data from
* date passed - 1 month. likewise the opposite if the direction is right, going to return to date + 1 month and the
* data that revolves around that month.
*
* @param direction
* @return html alteration
*/
var view_journal_dreams = function (direction = null) {
    let $journal_dreams = $('#journal_dreams'),
        view_journal_dreams_url = $journal_dreams.data('view_journal_dreams_url'),
        date = $journal_dreams.attr('data-date');

    $.ajax({
        method: 'get',
        url: view_journal_dreams_url,
        data: {
            direction: direction,
            date: date
        },
        success: function (data) {
            $journal_dreams.html(data.html);
            $journal_dreams.attr('data-date', data.date);
            $('.journal_dreams_date').html(data.date_display);
        }
    });
};