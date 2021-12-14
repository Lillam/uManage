$(() => {
    let $body = $('body');

    $body.on('click', '.tasks_navigation_left, .tasks_navigation_right', function (event) {
        event.preventDefault();
        $('.tasks').attr('data-get_tasks_url', $(this).attr('href'));
        view_tasks();
    });

    // when the page loads and there is a $('.tasks') object present in the dom... then we are going to be making a
    // a query to the database which will look for all tasks against the parameters that are set initially.
    view_tasks();
});

/**
* Default method for collecting tasks from the database, this will fire a request to the TaskController which will check
* what variables are currently existing on the page. the current variables of which will be utilised and needed are:
*
* Assuming these variables are present the system will utilise them otherwise they will be inserted as null and blank
* values and in essence, be ignored.
*
* @param direction
*/
var view_tasks = function () {
    let $tasks = $('.tasks'),
        url = $tasks.attr('data-get_tasks_url'),
        tasks_per_page = $tasks.data('items_per_page');

    if (typeof (url) !== "undefined" || url !== '') {
        $.ajax({
            method: 'get',
            url: url,
            data: {
                title: $tasks.data('title'),
                project_id: $tasks.data('project_id'),
                task_statuses: $('#task_statuses').val(),
                task_issue_types: $('#task_issue_types').val(),
                task_priorities: $('#task_priorities').val(),
                search: $('#task_search').val(),
                pagination: $tasks.data('pagination'),
                tasks_per_page: parseInt(tasks_per_page),
            },
            success: function (data) {
                var $task_navigation = $tasks.parent().find('.task_navigation'),
                    $task_left_navigation = $task_navigation.find('.tasks_navigation_left'),
                    $task_right_navigation = $task_navigation.find('.tasks_navigation_right'),
                    $tasks_count = $task_navigation.find('.count'),
                    $tasks_total = $task_navigation.find('.total');

                if (typeof (data.previous_url) !== "undefined" && typeof (data.next_url) !== "undefined") {
                    $tasks_count.html(data.count);
                    $tasks_total.html(data.total);

                    $task_left_navigation.attr('href', data.previous_url);
                    $task_right_navigation.find('.tasks_navigation_right').attr('href', data.next_url);

                    data.previous_url !== null
                        ? $task_left_navigation.attr('href', data.previous_url).parent().removeClass('uk-hidden')
                        : $task_left_navigation.attr('href', '').parent().addClass('uk-hidden');

                    data.next_url !== null
                        ? $task_right_navigation.attr('href', data.next_url).parent().removeClass('uk-hidden')
                        : $task_right_navigation.attr('href', '').parent().addClass('uk-hidden');
                } else {
                    $task_left_navigation.remove();
                    $task_right_navigation.remove();
                    $tasks_count.closest('div').remove();
                }

                $tasks.html(data.html);
            }
        });

        // if we have managed to accomplish the above, then we are going to want to return from this method here, so
        // that the console log below is never achieved. (if it does, then the url has not been set)
        return;
    }

    // we shouldn't get here providing that the url exists.
    console.log('The url was not found, check to see if there is $(.tasks) html dom element');
};

var initialise_board = function () {

};