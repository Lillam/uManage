$(() => {
    // assigning body to a variable, so we don't have to keep re-instantiating the body variable when applying further
    // on events.
    let $body = $('body');

    // applying an onclick event to the journal report calendar navigation (left and right) and when the user clicks on
    // one in particular, we will be checking the direction of the result, if it is left, then we are going to be
    // loading results into the past, otherwise, we are going to be loading results based in the future, this will always
    // be incremental and decremental of the data passed in question + 1 month || - 1 month.
    $body.on('click', '.journal_report_calendar_left, .journal_report_calendar_right', function (event) {
        let $this = $(this),
            direction = $this.hasClass('journal_report_calendar_left') ? 'left' : 'right';
        load_journal_report(direction);
    });

    // when the page first loads, we are going to want to load in all the journal reports for the date that will be
    // set against the journal reports wrapper... if one isn't set then we're essentially gathering all journal data
    // in report format (for this month)
    load_journal_report();
});

/**
* Method for loading journal reports for a set month (the month in question by default will be today, if we cannot find
* one from the url parameter, and have not yet passed one to the view, then we use today, otherwise, when we click left
* or right on the date navigation; then will be looking at (date x - 1) or (date x + 1) and go in the direction that the
* user in question has decided to click on. this will return a variety of data that will update a variety of elements
* across the board.
*
* @param direction | (string)
*/
var load_journal_report = function (direction = null) {
    let $journal_reports = $('.journal_reports'),
        view_journals_report_url = $journal_reports.data('view_journals_report_url'),
        date = $journal_reports.attr('data-date'),
        $statistic_1_star_days = $('.statistic_1_star_days'),
        $statistic_2_star_days = $('.statistic_2_star_days'),
        $statistic_3_star_days = $('.statistic_3_star_days'),
        $statistic_4_star_days = $('.statistic_4_star_days'),
        $statistic_5_star_days = $('.statistic_5_star_days'),
        $statistic_achievements = $('.statistic_achievements'),
        $journal_report_date = $('.journal_report_date');

    $.ajax({
        method: 'get',
        url: view_journals_report_url,
        data: {
            date: date,
            direction: direction
        },
        success: function (data) {
            // the overview report graph, this will be displaying the achievements and ratings as a bar chart side by
            // side so that we can compare the difference between how the overall rating of the day was and see if a
            // pattern occurs with how terrible a day might have gone.
            journal_report_graph('journal_report_overview_graph', 'bar', data, [
                'achievements', 'ratings'
            ]);

            // load a radar report graph which gives an overall visual view on how the month is going and has gone
            // and spreads out an instant visual for ratings this particular month. (patterns can be spotted looking
            // from month to month)
            journal_report_graph('journal_report_rating_graph', 'radar', data, [
                'ratings'
            ]);

            // load a rader report graph which gives an overall visual view on how the month is going and has gone
            // and spreads out an instant visual for achievements for this particular month. (patterns can be spotted
            // looking from month to month)
            journal_report_graph('journal_report_achievements_graph', 'radar', data, [
                'achievements'
            ]);

            // load a bar chart which looks at the content length of the lowest point and highest points for this month
            // and can pick up patterns depending on how much or how little content has been written against the month
            // which might have had a lower or higher rating... is the user rating higher when they write more content?
            // is the user not feeling like writing much at all with lower ratings etc.
            journal_report_graph('journal_report_words_count_graph', 'bar', data, [
                'highest_point', 'lowest_point'
            ]);

            $statistic_1_star_days.html(data.total_1_star_days);
            $statistic_2_star_days.html(data.total_2_star_days);
            $statistic_3_star_days.html(data.total_3_star_days);
            $statistic_4_star_days.html(data.total_4_star_days);
            $statistic_5_star_days.html(data.total_5_star_days);
            $statistic_achievements.html(data.total_achievements);

            $journal_reports.attr('data-date', data.date);
            $journal_report_date.html(data.date_display);

            // if the direction is not null, and we have a direction set left or right, then we are going to be
            // putting in the url paramaters, the date of which we are looking at, so that when we next refresh, or
            // decide to go back a page, then you're always going to be looking at the right view without needing too
            // much annoying back and forth clicking
            if (direction !== null) {
                let params = new URLSearchParams(window.location.search);
                params.set('date', data.date);
                history.pushState(
                    '',
                    '',
                    window.location.origin + window.location.pathname + '?' + params.toString() + window.location.hash
                );
            }
        }
    });
};

/**
* This method is strictly for quick setting up of graphs, if the page ever requires more than one graph type then this
* can be used in order for displaying the same information in a variety of different ways.
*
* @param graph_element | (string)
* @param graph_type    | (string)
* @param data          | (object)
* @param includes      | (array)
*                      | (string) ['ratings']
*                      | (string) ['achievements']
*                      | (string) ['highest_point']
*                      | (string) ['lowest_point']
*/
var journal_report_graph = function (
    graph_element,
    graph_type,
    data,
    includes = [ 'ratings', 'achievements', 'highest_point', 'lowest_point' ]
) {
    if (window[`${graph_element}_graph`])
        window[`${graph_element}_graph`].destroy();

    let data_sets = [];

    // if ratings exists in the includes array, then we are going to push this particular element to the array in
    // question so that we can report back in the graph, this specific set of data.
    if (includes.includes('ratings')) {
        data_sets.push({
            label:           'Rating',
            data:            data.journal_ratings,
            backgroundColor: data.journal_ratings_colors,
            borderWidth:     1
        });
    }

    // if achievements exists in the includes array, then wea re going to push this particular element to the array in
    // question so that we can report back in the graph, this specific set of data.
    if (includes.includes('achievements')) {
        data_sets.push({
            label:           'Achievements',
            data:            data.journal_achievements,
            backgroundColor: data.journal_achievements_colors[0],
            borderWidth:     1,
        });
    }

    if (includes.includes('highest_point')) {
        data_sets.push({
            label:           'Highest Points Character Count',
            data:            data.journal_highest_word_counts,
            backgroundColor: data.journal_highest_word_counts_colors,
            borderWidth:     1,
        });
    }

    if (includes.includes('lowest_point')) {
        data_sets.push({
            label:           'Lowest Points Character Count',
            data:            data.journal_lowest_word_counts,
            backgroundColor: data.journal_lowest_word_counts_colors,
            borderWidth:     1,
        });
    }

    let graph_options = {
        type: graph_type,
        data: {
            labels: data.labels,
            datasets: data_sets,
        }
    };

    // if the graph type, is bar, then we are always going to want to start at 0...
    if (graph_type === 'bar') {
        graph_options.options = {
            scales: {
                yAxes: [{ ticks: { beginAtZero: true, min: 0 }}]
            }
        };
    }

    // assign this graph to a variable, which will be checked when the data is refreshed, and if it exists, this will
    // get purged and re-assigned to a variable; with the new data in question.
    window[`${graph_element}_graph`] = new Chart(
        $(`.${graph_element}`),
        graph_options
    );
};