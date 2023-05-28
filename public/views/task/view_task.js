$(() => {
    let $body = $('body'),
        $taskTitle = $('.task_name_title');

    $body.on('keydown', '.task_name', function (event) {
        $taskTitle.html($(this).text().trim());

        if (event.key === 'Enter') {
            event.preventDefault();
            updateTask('name', $(this).text().trim());
        }
    });

    // applying to the body, some click events, currently we are going to be utilising this for stripping off the task
    // dropdown open class; so that we can have an easy method of closing the dropdown.
    $body.on('click',  () => {
        $('.task_dropdown_wrapper').removeClass('task_dropdown_open');
    });

    $body.on('click', '.task_dropdown_wrapper', function (event) {
        event.stopPropagation();
        let $this = $(this);
        $this.toggleClass('task_dropdown_open');
    });

    $body.on('click', '.task_dropdown_wrapper .task_dropdown a', function (event) {
        event.stopPropagation();
        let $this = $(this),
            field = $this.parent().attr('id'),
            value = $this.attr('data-id');

        $this.closest('.task_dropdown_wrapper').find('span').html($this.html());
        $this.closest('.task_dropdown_wrapper').removeClass('task_dropdown_open');

        // update the task with the field and value...
        updateTask(field, value);
    });

    // when clicking on the description, we are going to be instantiating summernote for the user to begin start making
    // amends to the description of the task, clicking on description will also look to it's parent element and find the
    // options for the description and un-hide them as they are hidden by default, we don't need to see these to begin
    // with as this will be wasting space, and rather than saving on blur, (which to me feels like a stupid functionality
    // it was best to offer the user the option to cancel or save their changes)
    $body.on('click', '.description', function (event) {
        let $this = $(this);
        handleSummernoteOpen('description', $this.html().trim());
        $this.parent().find('.description_options').removeClass('uk-hidden');
        $this.summernote(window.summernote_options).next().find('.note-editable').placeCursorAtEnd();
    });

    // this set of logic is devoted to handling the process logic for description editing, if you are to click cancel,
    // it will fire off a summernote leave function... which will simply put the content back to what it was prior to
    // the click, however if you are to click submit, then it will proceed to editing the task in question and then.
    $body.on('click', '.save_description, .cancel_description', function (event) {
        event.preventDefault();

        let $this = $(this),
            $description = $('.description'),
            handle= $this.hasClass('cancel') ? 'cancel' : 'save',
            field = 'description',
            updateOnSave = $this.hasClass('cancel');

        $description.summernote('destroy');

        $this.parent().addClass('uk-hidden');

        // if the element has the class of cancel, then we are simply going to want to pass in the summernote object
        // so that we are able to do something particular with it, and revert back to what the content once used to be
        // rather than viewing what the content is currently set to be.
        handleSummernoteLeave($description, handle, field, updateOnSave);

        // if the user has opted to press on save, rather than cancel then we are going to look to saving these changes
        // in the database after we have uninstantiated the summernote object, so that we can take the html of the
        // description jquery object.
        if ($this.hasClass('save_description')) {
            updateTask(field, $description.html());
        }
    });

    $body.on('click', '.task_info', function (event) {
        event.stopPropagation();
        let $task = $('#task');

        if (window.innerWidth <= 991) {
            $task.removeClass('task_sidebar_closed');
            $task.toggleClass('task_sidebar_open');
        }

        if (window.innerWidth > 991) {
            $task.toggleClass('task_sidebar_closed');
            $task.removeClass('task_sidebar_open');
        }
    });

    $body.on('click', () => {
        $('#task').removeClass('task_sidebar_open');
    });

    $body.on('click', '.task_sidebar', function (event) {
        event.stopPropagation();
        $('.task_dropdown_wrapper').removeClass('task_dropdown_open');
    });

    // when the page loads, we are going to want to load in the task widgets.
    // currently we are going to be loading in the following:
    // task comments
    // task checklists
    view_task_comments();
    view_task_checklists();
});

if (typeof Echo !== "undefined") {
    // we are going to keep a listen out for the pusher server; and if this makes a connection and hears something on the
    // task messages, then the website is going to render the return value on what wants to happen to the frontend of the
    // user in question... this will update a variety of areas on the frontend... such as the:
    // task description
    // task name
    // task statuses
    // task assignee
    // task owner
    // task due date and more...
    Echo.channel(`task_${$('#task').attr('data-task_id')}`)
        .listen('Task\\TaskMessage', (e) => {
            $(`.${e.field}`).html(e.value);
        });
}

/**
* Method for handling the process of updating a task, this will simply only the field and the value that we are hoping
* to pass through to the controller to process. if there needs to be a callback that happens after this has been
* completed then we are able to do so.
*
* @param field    (string)
* @param value    (variable)
* @param callback (function)
*/
const updateTask = function (field, value, callback = null) {
    let $task = $('#task'),
        url = $task.data('edit_task_url'),
        task_id = $task.data('task_id'),
        project_id = $task.data('project_id');

    request().post(url, { project_id, task_id, field, value })
        .then(data => {
            ajax_message_helper($('.ajax_message_helper'), data);
            if (callback !== null) {
                callback();
            }
        })
};