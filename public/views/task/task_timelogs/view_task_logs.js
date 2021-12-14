$(() => {
    let $body = $('body');

    // a method for being able to refresh the box segment, this method will allow the user to just click and have the
    // logs segment just refresh (this will be in case that they're wanting to make sure that their visuals are
    // currently up to date with what someone else might be working on).
    $body.on('click', '.reload.view_task_logs', function (event) {
        let $this = $(this);
        $this.find('i').addClass('fa-spin');
        view_task_logs();
    });

    $body.on('click', '.task_logs .paginate_previous, .task_logs .paginate_next', function (event) {
        let $this = $(this),
            direction = $this[0].classList[0];
        view_task_logs(direction.split('paginate_')[1]);
    });
});

/**
* Method for loading in the task log information, when a user has made an amend to any particular area of the task then
* this method is going to get called, so that we are able to instantly view the new log entry - and in other people's
* cases will be able to get themselves familiar with all the changes that will have been made to the particular task
* in question
*
* @param direction = string | previous, next
*/
var view_task_logs = function(direction = false) {
    let $task = $('#task'),
        $task_logs = $('.task_logs'),
        view_task_logs_url = $task_logs.data('view_task_logs_url'),
        task_id = $task.data('task_id'),
        page = parseInt($task_logs.attr('data-page'));

    if (direction === 'previous' && page !== 0) page -= 1;
    if (direction === 'next') page += 1;

    $task_logs.attr('data-page', page);

    $.ajax({
        method: 'get',
        url: view_task_logs_url,
        data: {
            task_id: task_id,
            page: page
        },
        success: function (data) {
            $task_logs.html(data.html);
            $('.view_task_logs.reload').find('i').removeClass('fa-spin');
        }
    });
};