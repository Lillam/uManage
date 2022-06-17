$(() => {
    // assigning the body variable, so we don't have to re-instantiate the body object every time we're going to be
    // needing to apply a click function...
    let $body = $('body');

    // when the user clicks on the advanced search button, then the sidebar will open itself, we will need to stop the
    // propagation so that we aren't going to be instantly closing the sidebar. this will open the ability for the user
    // to be able to apply some filtering to the project and it's tasks inside it.
    $body.on('click', '.project_advanced_search', function (event) {
        event.preventDefault();
        event.stopPropagation();

        let $project = $('#project');
        $project.toggleClass('sidebar_open');
    });

    // we need the ability to be able to close the project sidebar (filtering) so that the user will be able to return
    // to viewing the tasks inside the project.
    $body.on('click', '.close_project_sidebar', function (event) {
        $('#project').removeClass('sidebar_open');
    });

    // when clicking on the project sidebar, we are going to be wanting to stop the propagation of bubbling up to take
    // away the open class, the open class wants to remain when clicking anywhere inside the  project sidebar so that
    // the user will be able to decide whether the filters that they have applied are the one's that they are
    // wanting or not .
    $body.on('click', '.project_sidebar', function (event) {
        event.stopPropagation();
    });

    // when we are cycling through the users pushed history, and we are cycling back through the saved pushes, and when
    // we have done this we are going to apply the changes to the input filters... and re-organise the filters.
    $(window).on('popstate', function (event) {
        let params = new URLSearchParams(window.location.search),
            filters = decodeURIComponent(params.toString()).split('&');

        let checkbox_elements_and_values = [];

        filters.forEach(function (value) {
            let key_piece = value.split('='),
                input_class = key_piece[0],
                input_values = key_piece[1].split(',');

            checkbox_elements_and_values.push({
                element: input_class,
                values: input_values
            });

        }, checkbox_elements_and_values);
    });

    // this method is entirely for searching the table on this page, this method will be working from the keyup callback
    // which will redraw the table based on the information that will be sat in the table at the current iteration of
    // ajax data inside.
    $('#task_search').on('keypress', function (event) {
        let $this = $(this);

        // clearing the timeout for the window search... (this might be better off being named window.task_search_timeout
        // so that I'm able to utilise more than one timeout for search should I ever need to...
        clearTimeout(window.search_timeout);
        window.search_timeout = setTimeout(function () {
            // when the timeout has been hit we are going to want to actually fire up to the database and reload the
            // tasks based on what is currently in this input, nothing special really needs to happen here, we just want
            // to execute a particular query that looks for something in the database with what the user has searched.
            view_tasks()
        }, 500);
    });

    $body.on('click', '.clear_filters', function (event) {
        event.preventDefault();

        let $this = $(this),
            params = new URLSearchParams(window.location.search);

        // remove all the params from the header, as we will no longer be utilising this as we have requested to the
        // system to clear the filters...
        params.delete('task_status');
        params.delete('task_priority');
        params.delete('task_issue_type');

        $('#task_statuses').val('');
        $('#task_priorities').val('');
        $('#task_issue_types').val('');

        // clear everything inside the dropdown with altering the prop checked to equate to false.
        $('.project_sidebar input[type=checkbox]').prop('checked', false);

        // pushing these changes to the history, that the user will be able to cycle through... so that the user will
        // be able to link their current view to someone and the user coming will see just this
        history.pushState(
            '',
            '',
            window.location.origin + window.location.pathname + '?' + params.toString() + window.location.hash
        );

        // after we have cleared all the filters, we are going to want to refresh the table so that everything can
        // load back in...
        view_tasks();
    });

    // this logic set is checking if the user is selecting any of the designated checkboxes:
    // .task_status_checkbox
    // .task_issue_type_checkbox
    // .task_priority_checkbox
    // these checkboxes are going to be inside the filtration area of any task tabling system... this is globalised
    // to an element requiring a hidden input, and a variety of checkboxes with a designated data attribute assigned to
    // it for the ID pass.
    // all of these inputs will be in the format of 1,2,3,4,5,6,7,8,9,10 and be pushed tto a search where in those ids
    // once this has been accomplished the table/element will  be refreshed to accommodate the new search parameters.
    $body.on('change', '.task_status_checkbox, .task_issue_type_checkbox, .task_priority_checkbox', function (event) {
        let $this = $(this),
            $input = '',
            input_id_attribute = '';

        if ($this.hasClass('task_status_checkbox')) {
            $input = $('#task_statuses');
            input_id_attribute = 'task_status_id';
        }

        if ($this.hasClass('task_issue_type_checkbox')) {
            $input = $('#task_issue_types');
            input_id_attribute = 'task_issue_type_id';
        }

        if ($this.hasClass('task_priority_checkbox')) {
            $input = $('#task_priorities');
            input_id_attribute = 'task_priority_id';
        }

        let current_value = $input.val() !== '' ? $input.val() : '',
            new_value = '',
            params = new URLSearchParams(window.location.search);

        if ($this.prop('checked')) {
            new_value = current_value !== '' ?
                    `${current_value},` + $this.data(input_id_attribute) :
                    $this.data(input_id_attribute);
        } else {
            new_value = current_value
                .replace(`,${$this.data(input_id_attribute)}`, '')
                .replace($this.data(input_id_attribute), '');
        }

        // if the value has been left as a single ',' then we want to overwrite it with nothing.
        if (new_value === ',') {
            new_value = '';
        }

        // insert the new value to the hidden input.
        $input.val(new_value);

        // push this change to the url.
        if ($input.val() !== '') {
            params.set(input_id_attribute.replace('_id', ''), $input.val());
        } else {
            params.delete(input_id_attribute.replace('_id', ''));
        }

        // pushing these changes to the history, that the user will be able to cycle through... so that the user will
        // be able to link their current view to someone and the user coming will see just this
        history.pushState(
            '',
            '',
            window.location.origin + window.location.pathname + '?' + params.toString() + window.location.hash
        );

        // after we have done everything we have needed to do within this logic set, we are going to want to reload
        // the tasks...
        view_tasks();
    });

    // this method is literally just a method which will force a click on the sidebar add task/project
    // which will just give you the sidebar form for being able to add new projects or tasks...  which will allow the
    // to be able to add new projects on the page...
    $body.on('click', '.add_project_task_button', function (event) {
        event.preventDefault();
        event.stopPropagation();
        let $sidebar_add_new_task = $('.sidebar_add_new_task');

        $('#add_sidebar_button').click();

        if (! $sidebar_add_new_task.hasClass('active')) {
            $sidebar_add_new_task.click();
        }
    });
});