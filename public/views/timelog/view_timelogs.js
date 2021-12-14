$(() => {
    // document ready
    view_timelogs_box();
});

/**
* Method for returning timelogs... acquire designated timelogging formats which will be returned in the designated box
* wrapper that has a url against it and dump the data into the box.
*/
var view_timelogs_box = function () {
    let $timelogs_box = $('.timelogs_box'),
        view_timelogs_url = $timelogs_box.data('get_timelogs_url');

    $.ajax({
        method: 'get',
        url: view_timelogs_url,
        data: {},
        success: function (data) {
            $timelogs_box.html(data.html);
        }
    });
};