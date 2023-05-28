$(() => {
    let $body = $('body');

    $('#time_log_note').summernote(window.summernote_options);

    // Clicking on left or right on the time_log calendar bar, will cycle the user through the dates, taking the current
    // week into consideration, it will then take the stat of  the week, add another week onto that, and then this is
    // the new date range that  the user will be dealt with. after this has been clicked we are going to be looking at
    // the direction that the user has opted to cycle through the calendar and then bring back any results if any exist.
    $body.on('click', '.time_log_calendar_left, .time_log_calendar_right', function (event) {
        let $this = $(this),
            date = $('.time_log_calendar').attr('data-current_date'),
            direction = '';

        if ($this.hasClass('time_log_calendar_left')) {
            direction = 'left';
        }

        if ($this.hasClass('time_log_calendar_right')) {
            direction = 'right';
        }

        viewTimeLogs(date, direction);

        // todo | this is going to need to inject the current position into the url, so when the page refreshes the user
        //  will be back  to where t hey left off, prior to refreshing for whatever reason they may have felt necessary
    });

    // clicking on .add_time_log_entry will bring up a modal, the modal functionality is applied via uikit's
    // functionality with a target assigned to the modal. however, we need extra functionality on top of this, so we
    // can apply the date in question, to add time to.
    $body.on('click', '.add_time_log_entry', function (event) {
        let $this = $(this),
            $time_log_day_wrapper = $this.closest('.time_log_day_wrapper'),
            target_date = $time_log_day_wrapper.attr('data-date'),
            $add_time_log_modal = $('#add_time_log_modal');

        $add_time_log_modal.find('#from').val(target_date);
        $add_time_log_modal.find('#to').val(target_date);
    });

    // when the user has finished creating a time_log entry, they will need a button in order to click on for adding the
    // entry into the backend of the website. this button will be taking the values that we're storing below and make
    // a request with these details. once the system has created the entry and a response is given, we will be refreshing
    // the TimeLog calendar view.
    $body.on('click', '.make_time_log', function  (event) {
        let $this = $(this),
            $add_time_log_modal = $this.closest('#add_time_log_modal'),
            make_time_log_url   = $add_time_log_modal.data('make_time_log_url'),
            task_id             = $add_time_log_modal.find('#task_id').attr('data-task_id'),
            project_id          = $add_time_log_modal.find('#task_id').attr('data-project_id'),
            from                = $add_time_log_modal.find('#from').val(),
            to                  = $add_time_log_modal.find('#to').val(),
            time_spent          = $add_time_log_modal.find('#time_spent').val(),
            time_log_note       = $add_time_log_modal.find('#time_log_note').val();

        $.ajax({
            method: 'post',
            url: make_time_log_url,
            data: {
                task_id:       task_id,
                project_id:    project_id,
                from:          from,
                to:            to,
                time_spent:    time_spent,
                time_log_note: time_log_note
            },
            success: function (data) {
                viewTimeLogs($('.time_log_calendar').attr('data-current_date'));
            }
        })
    });

    // on the key up on the task_id input, we are going to be checking whether or not the input does not equal '' then
    // we will be opening the loader, which will then remove the spinner. if the task_name length is greater than or
    // equal to 3 then we will send up a request to find any task that somewhat matches on the name that we pass.
    // once we get the data back will be injected in the form of a dropdown  below the input, which will then be able
    // to be clicked on, the task_id will be appended on the name input.
    let task_search_timeout = null;
    $body.on('keyup', '#task_id', function (event) {
        let $this = $(this),
            search_tasks_url = $('#add_time_log_modal').data('search_tasks_url'),
            task_name = $this.val();

        clearTimeout(task_search_timeout);

        if (task_name.length >= 3) {
            $this.parent().find('i').css({
                display: task_name !== '' ? 'block' : 'none'
            });

            task_search_timeout = setTimeout(() => {
                $.ajax({
                    method: 'get',
                    url: search_tasks_url,
                    data: { name: task_name },
                    success: function (data) {
                        $this.parent().find('i').css({ display: 'none' });
                        $this.parent().find('.dropdown').remove();
                        $this.parent().append(data);
                    }
                });
            }, 500);
        } else {
            $this.parent().find('.dropdown').remove();
        }
    });

    // this functionality set is based on the above, when we have received the list of tasks (if any) from the above
    // data return, then we will be able to be clicking on one of the elements that are in the dropdown, once we do so
    // the name will be appended into the input box as well as the task_id being inserted on the attribute on the data of
    // the task_id element.
    $body.on('click', '.task_search_input_wrapper .dropdown li a', function (event) {
        let $this = $(this),
            task_id = $this.attr('data-task_id'),
            project_id = $this.attr('data-project_id'),
            task_name = $this.html();

        $this.closest('.task_search_input_wrapper').find('#task_id').val(task_name);
        $this.closest('.task_search_input_wrapper').find('#task_id').attr('data-task_id', task_id);
        $this.closest('.task_search_input_wrapper').find('#task_id').attr('data-project_id', project_id);
        $this.closest('.dropdown').remove();
    });

    // when the user clicks on "delete_time_log" we are going to capture what the time_log id is (this will be attached
    // as a data attribute on the time_log_entry...) so when the user clicks, we take the id of the data attribute and
    // pass it to the delete method so that the system will be able to take care of the method.
    $body.on('click', '.delete_time_log', function (event) {
        let $this = $(this),
            time_log_id = $this.closest('.time_log_entry').data('time_log_id');
        delete_time_log(time_log_id);
    });

    // Initialise the time logs on first initial view for the time logs, this will grab everything that is currently
    // within the date range of today, so if today is wednesday, it will take today, grab the start of the week from
    // today, also grab the end of the week from today, and add all days in between into an array , grabbing all
    // time logs from these ranges and instantiate them onto first page load.
    viewTimeLogs();
});

/**
* This function for returning all time logs in the system for a particular set date range, this will be working
* within weeks, this will be from start of week to end of week, every week. and this will be progressively working
* this will only be returning the necessary data between the 7 days date range. The system will also be returning
* the new current date, so we are able to then move onto the next or previous passively and progressively.
*
* @param date
* @param direction
*/
const viewTimeLogs = function (date = false, direction = false) {
    let viewTimeLogsUrl = $('.time_log_calendar').data('view_time_logs_url');

    request().get(viewTimeLogsUrl, { date, direction })
        .then(data => {
            $('.time_logs').html(data.html);
            $('.time_log_calendar').attr('data-current_date', data.date);
            $('.date').html(data.title);
        })
};

/**
* This method is entirely utilised for deleting the time_log in question; only the id will be passed as a parameter, and
* only the user (authenticated) user id matched will be able to delete the time_log in question.
*
* @param time_log_id
* @return void
*/
const delete_time_log = function (time_log_id) {
    let delete_time_log_url = $('.time_log_calendar').data('delete_time_log_url');

    request().get(delete_time_log_url, { time_log_id })
        .then(data => {
            // we only want to remove this from the view if the user has had the necessary permissions to do so,
            // otherwise we are just going to completely return void, and let the user know that nothing has happened
            // and that they did not have the necessary permissions to do this.
            if (typeof data.success !== "undefined") {
                $(`.time_log_entry[data-time_log_id="${time_log_id}"]`).remove();
            }
        })
};