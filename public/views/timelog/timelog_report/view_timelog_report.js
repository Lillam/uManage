$(() => {
    var $body = $('body');

    // if the user clicks on timelog report navigation left, or timelog report navigation right then we are going to
    // call the view_timelog_report with a direction left or right, which will either return a date that will be a
    // subtracted or an added to month... this allows us to cycle through perpetually through the reports...
    $body.on('click', '.timelog_report_navigation_left, .timelog_report_navigation_right', function (event) {
        var direction = $(this)[0].classList[0].includes('left') ? 'left' : 'right';
        view_timelog_report(direction);
    });

    // when the page loads, we are going to view_timelog_report method and ensure that the report is loaded in on the
    // page.
    view_timelog_report();
});

/**
* This method is for viewing the timelog report... by default on page load, the date will be the current month, if
* a month has been passed then we are going to be utilising that, otherwise we are going to take this month and
* subtract one month or add one month depending on the passed direction.
*
* @param direction
*/
var view_timelog_report = function (direction = null) {
    var $timelog_reports = $('.timelog_reports'),
        $timelog_reports_navigation = $('.timelog_report_navigation').find('.timelog_report_date'),
        view_timelog_reports_url = $timelog_reports.data('view_timelog_reports_url'),
        date = $timelog_reports.attr('data-date');

    $.ajax({
        method: 'get',
        url: view_timelog_reports_url,
        data: { date: date, direction: direction },
        success: function (data) {
            $timelog_reports.attr('data-date', data.date);
            $timelog_reports_navigation.html(data.display_date);

            // setting up the project timelogging report graph...
            setup_chart('timelog_report_by_project', 'bar', {
                labels: data.project_labels,
                datasets: [{
                    label: '# of Hours by project',
                    data: data.project_values,
                    backgroundColor: data.project_colors.map(project_color => '#' + project_color),
                    borderWidth: 1
                }]
            });

            // setting up the task timelogging report graph...
            setup_chart('timelog_report_by_task', 'bar', {
                labels: data.task_labels,
                datasets: [{
                    label: '# of Hours by task',
                    data: data.task_values,
                    backgroundColor: data.task_colors.map(task_color => '#' + task_color),
                    borderWidth: 1
                }]
            });

            // setting up the day timelogging report graph...
            setup_chart('timelog_report_by_day', 'bar', {
                labels: data.daylog_labels,
                datasets: [{
                    label: '# of Hours by day',
                    data: data.daylog_values,
                    backgroundColor: data.daylog_colors,
                    borderWidth: 1
                }]
            });
        }
    });
};