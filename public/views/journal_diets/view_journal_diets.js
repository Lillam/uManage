$(() => {
    let $body = $('body');

    $body.on('click', '.journal_diet_calendar_left, .journal_diet_calendar_right', function (event) {
        let $this = $(this),
            direction = $this.hasClass('journal_diet_calendar_left') ? 'left' : 'right';

        viewJournalDiets(direction);
    });

    viewJournalDiets();
});

var viewJournalDiets = (direction = null) => {
    let $journalDiets = $('#journal_diets'),
        view_journal_diets_url = $journalDiets.data('view_journal_diets_url'),
        date = $journalDiets.attr('data-date');

    request().get(view_journal_diets_url, { direction, date }).then((data) => {
        $journalDiets.html(data.html);
        $journalDiets.attr('data-date', data.date);
        $('.journal_diet_date').html(data.date_display);

        if (direction) {
            addToHistory('date', data.date);
        }
    });
};
