$(() => {
    let $body = $('body');

    // apply a click event to the journal calendar navigation (left | right) -> depending on which one the user is going
    // to be clicking on will depend on which action gets taken. If te user clicks on left, then we are going to be
    // reloading the journals into the past, otherwise the user has clicked right and we are going to be loading journal
    // entries in the future, if the user clicks left we are going to fire up an ajax request with what is the current
    // assigned date, and - 1 month so we can navigate into the past, and likewise for clicking right, we are + 1 month
    // so we can navigate into the future, always between 1 month intervals.
    $body.on('click', '.journal_calendar_left, .journal_calendar_right', function (event) {
        let $this = $(this),
            direction = $this.hasClass('journal_calendar_left') ? 'left' : 'right';
        viewJournals(direction);
    });

    // when the page first loads, we are going to be loading in all the view journals (this will be one of the first
    // methods that runs so that the user will have visuals of all the journals on the page).
    viewJournals();
});

/**
* This is the method for finding and returning journals as a listing (calendar) view style, which will simply return
* all journal entries (plus one's that don't exist yet) so that we are able to click on dates and create an entry for
* this particular day. This is going to return the view of journals to the page so that we have a means for viewing
* and editing journal entries.
*
* @param direction (string)
*/
var viewJournals = function (direction = null) {
    let $journals = $('#journals'),
        view_journals_url = $journals.data('view_journals_url'),
        date = $journals.attr('data-date');

    request().get(view_journals_url, { direction, date }).then((data) => {
        $journals.html(data.html);
        $journals.attr('data-date', data.date);
        $('.journal_date').html(data.date_display);
        $('.journal_progress').find('.progress_percent').attr('style', `width: ${data.journal_percentage}%;`);

        // if the direction is not null, and we have a direction set left or right, then we are going to be
        // putting in the url parameters, the date of which we are looking at, so that when we next refresh, or
        // decide to go back a page, then you're always going to be looking at the right view without needing too
        // much annoying back and forth clicking
        if (direction) {
            addToHistory('date', data.date);
        }
    })
};