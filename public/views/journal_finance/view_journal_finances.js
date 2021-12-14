$(() => {
    let $body = $('body');

    $body.on('click', '.journal_finance_calendar_left, .journal_finance_calendar_right', function (event) {
        let $this = $(this),
            direction = $this.hasClass('journal_finance_calendar_left') ? 'left' : 'right';
        view_journal_finances(direction);
    });

    // when the page first loads, we are going to be loading in all the journal finances (this will be one of the first
    // methods that runs so that the user will have visuals of all the journals on the page).
    view_journal_finances();
});

/**
* This is the method for finding and returning journal finances as a listing (calendar) view style, which will simply
* return all journal finance entries (plus one's that don't exist yet). so we are able to load them up and begin
* entering some more entries should the user ever need to or care to do so.
*
* @param direction
*/
var view_journal_finances = function (direction = null) {
    let $journal_finances = $('#journal_finances'),
        view_journal_finances_url = $journal_finances.data('view_journal_finances_url'),
        date = $journal_finances.attr('data-date');

    $.ajax({
        method: 'get',
        url: view_journal_finances_url,
        data: {
            direction: direction,
            date: date
        },
        success: function (data) {
            $journal_finances.html(data.html);
            $journal_finances.attr('data-date', data.date);
            $('.journal_finance_date').html(data.date_display);

            // if the direction has been passed and isn't null, aka; wasn't the first initial page load and has been set
            // to either left or right. Upon this happening the system is going to append the next date or previous date
            // into the url string so that the user upon refreshing will be right back where they started.
            if (direction !== null) {
                let params = new URLSearchParams(window.location.search);
                params.set('date', data.date);
                history.pushState(
                    '',
                    '',
                    window.location.origin + window.location.pathname +
                        "?" + params.toString() + window.location.hash
                );
            }
        }
    });
};