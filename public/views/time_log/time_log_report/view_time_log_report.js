$(() => {
    let $body = $('body');

    // if the user clicks on time_log report navigation left, or time_log report navigation right then we are going to
    // call the view_time_log_report with a direction left or right, which will either return a date that will be a
    // subtracted or an added to month... this allows us to cycle through perpetually through the reports...
    $body.on('click', '.time_log_report_navigation_left, .time_log_report_navigation_right', function (event) {
        let direction = $(this)[0].classList[0].includes('left') ? 'left' : 'right';
        view_time_log_report(direction);
    });

    // when the page loads, we are going to view_time_log_report method and ensure that the report is loaded in on the
    // page.
    view_time_log_report();
});

/**
* This method is for viewing the time_log report... by default on page load, the date will be the current month, if
* a month has been passed then we are going to be utilising that, otherwise we are going to take this month and
* subtract one month or add one month depending on the passed direction.
*
* @param direction
*/
const view_time_log_report = function (direction = null) {
    let $time_log_reports = $('.time_log_reports'),
        $time_log_reports_navigation = $('.time_log_report_date'),
        view_time_log_reports_url = $time_log_reports.data('view_time_log_reports_url'),
        date = $time_log_reports.attr('data-date');

    $.ajax({
        method: 'get',
        url: view_time_log_reports_url,
        data: { date: date, direction: direction },
        success: function (data) {
            $time_log_reports.attr('data-date', data.date);
            $time_log_reports_navigation.html(data.display_date);

            // setting up the project time logging report graph...
            setup_chart('time_log_report_by_project', 'bar', {
                labels: data.project_labels,
                datasets: [{
                    label: '# of Hours by project',
                    data: data.project_values,
                    backgroundColor: data.project_colors.map(project_color => '#' + project_color),
                    borderWidth: 1
                }]
            });

            // setting up the task time logging report graph...
            setup_chart('time_log_report_by_task', 'bar', {
                labels: data.task_labels,
                datasets: [{
                    label: '# of Hours by task',
                    data: data.task_values,
                    backgroundColor: data.task_colors.map(task_color => '#' + task_color),
                    borderWidth: 1
                }]
            });

            // setting up the days time logging report graph...
            setup_chart('time_log_report_by_day', 'bar', {
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