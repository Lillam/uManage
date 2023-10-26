$(() => {
    view_task_report();
});

const view_task_report = function () {
    let $task_report = $('.task_report'),
        view_task_report_url = $task_report.data('get_task_report_url');

    $.ajax({
        method: 'get',
        url: view_task_report_url,
        data: {},
        success: function (data) {
            console.log(data);
            ['tasks_in_todo', 'tasks_in_progress', 'tasks_in_completed', 'tasks_in_archived'].forEach((item, key) => {
                const task_key = key + 1,
                      percent = Math.ceil(data.tasks[task_key].count / data.total_tasks * 100),
                      color = data.tasks[task_key].color,
                      element = $(`.${item}`);

                element.attr('style', `--c: ${color}; --p: ${percent}; --b: 10px;`);
                element.html(`${percent}%`);
            });

            // setupChart('tasks_in_todo', 'doughnut', {
            //     datasets: [{
            //         data: data.tasks_in_todo,
            //         backgroundColor: data.tasks_in_todo_colors,
            //         borderWidth: 1
            //     }],
            //     labels: data.tasks_in_todo_labels
            // });
            // setupChart('tasks_in_progress', 'doughnut', {
            //     datasets: [{
            //         data: data.tasks_in_progress,
            //         backgroundColor: data.tasks_in_progress_colors,
            //         borderWidth: 1
            //     }],
            //     labels: data.tasks_in_progress_labels
            // });
            // setupChart('tasks_in_completed', 'doughnut', {
            //     datasets: [{
            //         data: data.tasks_in_completed,
            //         backgroundColor: data.tasks_in_completed_colors,
            //         borderWidth: 1
            //     }],
            //     labels: data.tasks_in_completed_labels
            // });
            // setupChart('tasks_in_archived', 'doughnut', {
            //     datasets: [{
            //         data: data.tasks_in_archived,
            //         backgroundColor: data.tasks_in_archived_colors,
            //         borderWidth: 1
            //     }],
            //     labels: data.tasks_in_archived_labels
            // });
        }
    });
};