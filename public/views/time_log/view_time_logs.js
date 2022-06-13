// document ready
$(() => {
    view_time_logs_box();
});

/**
* Method for returning time logs... acquire designated time logging formats which will be returned to the designated box
* wrapper that has an url against it and dump the data into the box.
*/
var view_time_logs_box = function () {
    let $time_logs_box = $('.time_logs_box'),
        view_time_logs_url = $time_logs_box.data('get_time_logs_url');

    $.ajax({
        method: 'get',
        url: view_time_logs_url,
        data: {},
        success: function (data) {
            $time_logs_box.html(data.html);
        }
    });
};