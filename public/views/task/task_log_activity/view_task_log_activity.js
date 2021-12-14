$(() => {
    // ??
    // view_task_log_activity();
});

var view_task_log_activity = function () {
    var $task_log_activity = $('.task_log_activity'),
        view_task_log_activity_url = $task_log_activity.attr('data-view_task_log_activity_url'),
        task_id = $task_log_activity.data('task_id'),
        project_id = $task_log_activity.data('project_id');

    $.ajax({
        method: 'get',
        url: view_task_log_activity_url,
        data: {
            project_id: project_id,
            task_id: task_id
        },
        success: function (data) {
            $task_log_activity.html(data);
        }
    });
};