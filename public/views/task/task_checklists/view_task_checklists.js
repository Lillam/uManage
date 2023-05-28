$(() => {
    const $body = $('body');

    /**
    * Task Checklist Oriented Logic...
    *
    * this code is specifically dedicated to handling all of the processes that entails checklists... the code for
    * checklists and checklist items could be joined, however for the sake of segregating the functionality, I have split
    * their logic sets so that I can easier further entail specific updates to particular elements rather than having to
    * think about both options respectively.
    */

    // a method for being able to refresh the box segment, this method will allow the user to just click and have the
    // checklist segment just refresh (this will be in case that they're wanting to make sure that their visuals are
    // currently up to date with what someone else might be working on).
    $body.on('click', '.reload.view_task_checklists', function (event) {
        let $this = $(this);
        $this.find('i').addClass('fa-spin');
        view_task_checklists();
    });

    // On the focus of checklist names, this will find the next element and remove the hidden element (which will show
    // the save or cancel options when focusing... if we don't already have a cache set for it, then we are going to
    // set the cache for this element). if we do happen to have a cache element for this, we will iterate over it
    // until the user has either decided to cancel or save, which save will delete the cache key, causing the cache
    // to be re-cached on the new value
    $body.on('focus', '.task_checklist_name', function (event) {
        let $this = $(this),
            task_checklist_id = $this.closest('.task_checklist').data('task_checklist_id');

        $this.next().removeClass('uk-hidden');

        if (! cache().get(`task_checklist_${task_checklist_id}`))
            cache().set(`task_checklist_${task_checklist_id}`, $this.html().trim());
    });

    // On the blur of checklist names, this will look inside the cache for this element, if we have a value stored in
    // the cache, and the cache is the exact same as the current value, then we will assume the user has opted to not
    // make any changes, and has done editing this particular set, which we will no longer need to show the options
    // to save or cancel.. however, if there was a value set differently to the cache and looks away, the cancel and save
    // options will remain so that the user will know by default that there are some unsaved changes.
    $body.on('blur', '.task_checklist_name', function (event) {
        let $this = $(this),
            task_checklist_id = $this.closest('.task_checklist').data('task_checklist_id');

        if (cache().get(`task_checklist_${task_checklist_id}`) === $this.html().trim())
            $this.next().addClass('uk-hidden');
    });

    // On the key up of checklist item name, we will be checking the cache or the element itself to see whether or not
    // the values are either matching what we have in the cache, or if the element is empty, if they are empty then we
    // are going to remove the ability to try and save, same for if the values match the previous values of what they
    // once was, there's no need to try and save the same value or empty values, otherwise if the values aren't matching
    // and is not empty, then we will be removing the 'disabled' class from the button which will allow the user to
    // save the checklist.
    $body.on('keyup', '.task_checklist_name', function (event) {
        let $this = $(this),
            task_checklist_id = $this.closest('.task_checklist').data('task_checklist_id');

        if ($this.next().hasClass('uk-hidden'))
            $this.next().removeClass('uk-hidden');

        if (
            $this.html().trim() !== '' &&
            $this.html().trim() !== cache().get(`task_checklist_${task_checklist_id}`)
        ) {
            $this.next().find('.save_task_checklist_name').removeClass('disabled');
        } else {
            $this.next().find('.save_task_checklist_name').addClass('disabled');
        }

        if (event.ctrlKey && event.key === 'Enter')
            $this.next().find('.save_task_checklist_name').trigger('click');
    });

    // On the click of save_task_checklist_name button, which will only show if the values are different and not empty
    // when clicking on this button it will check if the element itself does not have the class of disabled, and if it
    // doesn't, it will disregard the click until the disabled has been removed, clicking this will save the checklist
    // name, and remove the previous value from the cache, so that the cache can get reset to the newest value in question
    // to the one we just updated it to, if the user needs to re-edit the checklist name.
    $body.on('click', '.save_task_checklist_name', function (event) {
        let $this = $(this),
            task_checklist_id = $this.closest('.task_checklist').data('task_checklist_id'),
            edit_task_checklist_url = $('.task_checklists_wrapper').data('edit_task_checklist_url'),
            name = $this.parent().prev().html().trim();

        if (! $this.hasClass('disabled') && name !== '') {
            $.ajax({
                method: 'post',
                url: edit_task_checklist_url,
                data: {
                    task_checklist_id: task_checklist_id,
                    name: name
                },
                success: function (data) {
                    ajax_message_helper($('.ajax_message_helper'), data);
                    cache().remove(`task_checklist_${task_checklist_id}`);
                    $this.parent().addClass('uk-hidden');
                    $this.addClass('disabled');

                    view_task_logs();
                }
            });
        }
    });

    // on the click of cancel_task_checklist_name will set the task_checklist_name value to be what it was in the cache
    // and then remove the cache from value, so that the value can get reset when the user next clicks on the element
    // this button is entirely used for when the user has decided that they do not want to proceed with the changes that
    // they was trying to make.
    $body.on('click', '.cancel_task_checklist_name', function (event) {
        let $this = $(this),
            task_checklist_id = $this.closest('.task_checklist').data('task_checklist_id');
        $this.parent().prev().html(cache().get(`task_checklist_${task_checklist_id}`));
        cache().remove(`task_checklist_${task_checklist_id}`);
        $this.prev().addClass('disabled');
        $this.parent().addClass('uk-hidden');
    });

    // on the focu  of the new_task_checklist_input, we are going to want to remove the hidden class from the wrapper
    // that contains the save option, as the users will need the method of being able to save the new checklist item,
    // when focusing the element in question, it will remove the hidden class and the button to save will then be visible
    // to the user.
    $body.on('focus', '.new_task_checklist_input', function (event) {
        let $this = $(this);
        $this.parent().next().removeClass('uk-hidden');
    });

    // when bluring from the new_task_checklist_input element, we are going to want to check what the value is of the
    // input, if the value is empty then we are going to want to add the hidden to the parent element that wraps the
    // save button, as there is nothing to save, however if the value has been filled, then we are going to keep the
    // option to save open so that the user will then be able to decide whether or not they are going to save as well as
    // having the visual representation that there are some unsaved changes.
    $body.on('blur', '.new_task_checklist_input', function (event) {
        let $this = $(this);
        if ($this.val() === '')
            $this.parent().next().addClass('uk-hidden');
    });

    // on the keyup of the new_task_checklist_input element, we are going to check if the value is empty, if it is empty
    // then we are going to add the 'disabled' class onto the save button, however , if the value does not equal empty,
    // then wea re going to add the disabled to the save feature, we are only going to want to allow the button to be
    // pressed if there is a value inside the element...
    // if the user opts to press enter on the element, then we are going to find the save button and emulate a trigger
    // click.
    $body.on('keyup', '.new_task_checklist_input', function (event) {
        let $this = $(this);

        if ($this.val() === '')
            $this.parent().next().find('a').addClass('disabled');

        if ($this.val() !== '')
            $this.parent().next().find('a').removeClass('disabled');

        if (event.key === 'Enter')
            $this.parent().next().find('a').click();
    });

    // when clicking on the element "save_new_task_checklists"  we will be checking whether or not the  element in
    // question has the class 'disabled' and if it does, this method will return nothing, otherwise this will make a
    // request to insert the new task checklist. once this has been completed, an alert that the action has been processed
    // will be displayed to the user and will disappear at a current interval of (1.5 seconds) after the checklist
    // has been inserted into the databases and the element has been refreshed, the user's focus will be inerted onto the
    // checklist item input for that new checklist group.
    $body.on('click', '.save_new_task_checklist', function (event) {
        let $this = $(this),
            make_task_checklist_url = $('.task_checklists_wrapper').data('make_task_checklist_url'),
            $task = $('#task'),
            task_id = $task.data('task_id'),
            project_id = $task.data('project_id'),
            $task_checklist_name_input = $this.closest('.new_task_checklist').find('.new_task_checklist_input'),
            task_checklist_name = $task_checklist_name_input.val(),
            order = parseInt($('.task_checklists_wrapper').find('.task_checklist').length + 1);

        if (! $this.hasClass('disabled')) {
            $.ajax({
                method: 'post',
                url: make_task_checklist_url,
                data: {
                    project_id: project_id,
                    task_id: task_id,
                    task_checklist_name: task_checklist_name,
                    order: order
                },
                success: function (data) {
                    ajax_message_helper($('.ajax_message_helper'), data);
                    view_task_checklists();
                    $task_checklist_name_input.val('');
                    setTimeout(function () {
                        $(`.task_checklist[data-task_checklist_id="${data.task_checklist_id}"]`)
                            .find('.new_task_checklist_item_input')
                            .trigger('focus');
                    }, 100);
                    view_task_logs();
                }
            });
            $this.addClass('disabled');
            $this.parent().addClass('uk-hidden');
        }
    });

    // this element will handle the deleting of the checklist group, clicking this will delete all the checklist item
    // elements within the checklist, as  if the checklist doesn't exist, it will  delete and cascade against all
    // the checklist items to delete  them too, rather than refreshing t he checklist segment, this will simply remove
    // the  element checklist to cut down on the need for refreshing all elements, a point of optimal handling.
    $body.on('click', '.delete_task_checklist', function (event) {
        event.stopPropagation();
        let $this = $(this),
            $task = $('#task'),
            task_id = $task.data('task_id'),
            project_id = $task.data('project_id'),
            task_checklist_id = $this.closest('.task_checklist').data('task_checklist_id'),
            delete_task_checklist_url = $('.task_checklists_wrapper').data('delete_task_checklist_url');

        $.ajax({
            method: 'post',
            url: delete_task_checklist_url,
            data: {
                project_id: project_id,
                task_id: task_id,
                task_checklist_id: task_checklist_id
            },
            success: function (data) {
                ajax_message_helper($('.ajax_message_helper'), data);
                $this.closest('.task_checklist').remove();
                view_task_logs();
            }
        });
    });

    // when the user has stopped interacting with the checklist ordering, the system will loop through all of the
    // checklists, to see what order they are currently in, in terms of their key to the item and where they are currently
    // appearing in the html stack, once the system has stopped setting up this order, will make a request to the backend
    // with the appropriate format [id => order] i.e... [1 => 1, 2 => 2, 10 => 3, 14 => 4, 16 => 5]. and the controller
    // for checklists will assort this in kind, once this has happened the user will be greeted with a success confirmation
    // that the ordering has been updated.
    $body.on('stop.uk.sortable', '.task_checklists', function (event) {
        let $this = $(this),
            $task = $('#task'),
            task_id = $task.data('task_id'),
            project_id = $task.data('project_id'),
            $task_checklists = $this.find('.task_checklist'),
            edit_task_checklist_order_url = $('.task_checklists_wrapper').data('edit_task_checklist_order_url'),
            task_checklists_order = [];

        $task_checklists.each(function (key, item) {
            let $item = $(item),
                task_checklist_id = $item.attr('data-task_checklist_id');

            task_checklists_order.push({
                task_checklist_id: task_checklist_id,
                order: (key + 1),
                project_id: project_id,
                task_id: task_id
            });
        });

        $.ajax({
            method: 'post',
            url: edit_task_checklist_order_url,
            data: { task_checklists: task_checklists_order },
            success: function (data) {
                ajax_message_helper($('.ajax_message_helper'), data);
            }
        });
    });

    $body.on('click', '.task_checklist_zip', function (event) {
        let $this = $(this),
            $task = $('#task'),
            task_id = $task.data('task_id'),
            project_id = $task.data('project_id'),
            $task_checklist = $this.closest('.task_checklist'),
            task_checklist_id = $task_checklist.attr('data-task_checklist_id'),
            edit_task_checklist_zip_url = $task_checklist.closest('.task_checklists_wrapper')
                                              .attr('data-edit_task_checklist_zipped_url'),
            is_zipped = $task_checklist.hasClass('zipped') ? 0 : 1;

        $.ajax({
            method: 'post',
            url: edit_task_checklist_zip_url,
            data: {
                task_checklist_id: task_checklist_id,
                is_zipped: parseInt(is_zipped),
                task_id: task_id,
                project_id: project_id
            },
            success: function (data) {
                $task_checklist = is_zipped === 1
                    ? $task_checklist.addClass('zipped')
                    : $task_checklist.removeClass('zipped');
            }
        });
    });

    /**
    *  Task Checklist Item Logic.
    *  All logic from here will be dedicated to task checklist items... all logic here could be closely paired with the
    *  task checklist logic however I have chosen to segregate the logic set so that i can be more spefific to each
    *  section of what this module is doing. and if something needs to happen differently to checklist items than it does
    *  to the checklists in question, then i'd be able to do that without creating too much cross over code. all logic
    */

    // when the user has opted to focus on the task_checklist_item_name element then  if we haven't already stored a
    // cache value for the element in question, then we are going to save a new one, and if the user opts to navigate
    // away but click on it again, it won't re-cache the value, because the current value hasn't yet been changed.
    // focusing this element will also remove the hidden option for the save/cancel elements, as they will need to show
    // so that the user will be able to either submit their changes or cancel them if they decide to back out.
    $body.on('focus', '.task_checklist_item_name', function (event) {
        let $this = $(this),
            task_checklist_item_id = $this.closest('.task_checklist_item').data('task_checklist_item_id');

        $this.next().removeClass('uk-hidden');

        if (! cache().get(`task_checklist_item_${task_checklist_item_id}`))
            cache().set(`task_checklist_item_${task_checklist_item_id}`, $this.html().trim());
    });

    // if the user is blurring away from the element task_checklist_item_name then we are going to check if the value
    // that currently sits in the element is same as the value we have stored in the cache, and if it has then we
    // are going to hide the save/cancel buttons as they will no longer be necessary to see, if the user has made a change
    // and blurred away from the element, is no longer focusing then the buttons will want to remain open so that it is
    // obvious that the user has some unsaved changes, this will also hide the elements for saving/cancelling if the
    // element is empty.
    $body.on('blur', '.task_checklist_item_name', function (event) {
        let $this = $(this),
            task_checklist_item_id = $this.closest('.task_checklist_item').data('task_checklist_item_id');

        if (
            $this.html().trim() === '' ||
            cache().get(`task_checklist_item_${task_checklist_item_id}`) === $this.html().trim()
        ) $this.next().addClass('uk-hidden');
    });

    // this set of logic is designated for checking the value of the input every time the user is making a change to the
    // task checklist item name, if the value is the same as the value we have in the cache, then we are going to
    // re-add the disable class, otherwise we are going to remove it if it is different, if the element is empty, then we
    // are also going to remove the disabled class so that the changes can be saved as there are differences to change.
    $body.on('keyup', '.task_checklist_item_name', function (event) {
        let $this = $(this),
            task_checklist_item_id = $this.closest('.task_checklist_item').data('task_checklist_item_id');

        if (
            $this.html().trim() !== '' &&
            $this.html().trim() !== cache().get(`task_checklist_item_${task_checklist_item_id}`)
        ) {
            $this.next().find('.save_task_checklist_item_name').removeClass('disabled');
        } else {
            $this.next().find('.save_task_checklist_item_name').addClass('disabled');
        }
    });

    // on the focus of the new task checklist item input, we are going to remove the hidden class from the options
    // wrapper so that the user will be able to press on the save button.
    $body.on('focus', '.new_task_checklist_item_input', function (event) {
        let $this = $(this);
        $this.parent().next().removeClass('uk-hidden');
    });

    // on the blur of the new_task_checklits_item_input and the value happens to be empty, then we are going to be
    // re-adding the class uk-hidden on the options wrapper (save button) as the options will no longer be needed to be
    // shown to the user, this will keep a more slicker visuals and a nicer flow feel to the system in general.
    $body.on('blur', '.new_task_checklist_item_input', function (event) {
        let $this = $(this);
        if ($this.val() === '')
            $this.parent().next().addClass('uk-hidden');
    });

    // on the keyup of the new_task_checklist_item_input, we are checking if the value does not equal blank, and if it
    // does we are going to find the submit button and remove the disabled class, however if the value is empty, then
    // we are going to be re-adding the class. if the user is opting to press enter inside this element, then we are
    // going to look for the submit button and trigger a click.
    $body.on('keyup', '.new_task_checklist_item_input', function (event) {
        let $this = $(this);

        if ($this.val() !== '')
            $this.parent().next().find('a').removeClass('disabled');

        if ($this.val() === '')
            $this.parent().next().find('a').addClass('disabled');

        if (event.key === 'Enter' && $this.val() !== '')
            $this.parent().next().find('a').trigger('click');
    });

    // on the click of saving the new_task_checklist_item,  we are going to be checking if this particular button in
    // question has the class of disabled, and if it does, this will return nothing, however if the class disabled does
    // not exist on the button then we are going to proceed with sending the data up to the database, and then we will be
    // spitting back a json response in here. on the submit of a new checklist item, we will give a confirmation to the
    // user that something has happened as well as re-loading the particular checklist we have opted to change, update
    // with the new checklist item in question, the entire checklist item set will be reloaded however, just for this one
    // checklist group , rather than refreshing the entire checklist groups in total.
    // after reloading the new checklist item set, we will be putting the focus back on the element that created it in
    // the first place so that the user can opt to quickly spam through creating a load of checklist items.
    $body.on('click', '.save_new_task_checklist_item', function (event) {
        let $this = $(this),
            $task = $('#task'),
            task_id = $task.data('task_id'),
            project_id = $task.data('project_id'),
            make_new_checklist_item_url = $('.task_checklists_wrapper').data('make_task_checklist_item_url'),
            $task_checklist_item_input = $this.closest('.new_task_checklist_item').find('.new_task_checklist_item_input'),
            task_checklist_item_name = $task_checklist_item_input.val(),
            task_checklist_id = $this.closest('.task_checklist').data('task_checklist_id'),
            order = $this.closest('.task_checklist').find('.task_checklist_item').length,
            $checklist = $this.closest('.task_checklist');

        if (! $this.hasClass('disabled')) {
            $.ajax({
                method: 'post',
                url: make_new_checklist_item_url,
                data: {
                    task_checklist_item_name: task_checklist_item_name,
                    task_checklist_id: task_checklist_id,
                    order: order,
                    task_id: task_id,
                    project_id: project_id,
                },
                success: function (data) {
                    ajax_message_helper($('.ajax_message_helper'), data);
                    view_task_checklist_items(task_checklist_id);
                    $this.addClass('disabled');
                    $task_checklist_item_input.val('');
                    $task_checklist_item_input.trigger('focus');

                    // recalibrating the checklist item progress bar based on the checklist items that are currently sat within this
                    // group.
                    setTimeout(function () {
                        recalibrate_task_checklist_progress($checklist);
                    }, 100);

                    view_task_logs();

                    // if we pass back to the system that the task checklist has been unzipped, then we are simply going
                    // to remove the class "zipped" from the checklist wrapper; if the user has opted to add something
                    // to the checklist then it means that it doesn't really want to be zipped.
                    if (data.task_checklist_unzipped === true) {
                        $this.closest('.task_checklist').removeClass('zipped');
                    }
                }
            });
        }
    });

    // on the click of the cancel_task_checklist_item_name, we are going to be finding the cache value that was stored
    // prior to making any amends, we are going to take this value and place that value as the old value of the
    // task_checklist_item_name old, and then delete the cache, so that the cache will be able to re-store what the
    // value is, this will work in conjunction with the saving as this will be doing a similar action, upon clicking
    // cancel, these buttons will be hidden as well as the input being recalculated based on cache.
    $body.on('click', '.cancel_task_checklist_item_name', function (event) {
        let $this = $(this),
            task_checklist_item_id = $this.closest('.task_checklist_item').data('task_checklist_item_id'),
            $task_checklist_item_name_input = $this.closest('.task_checklist_item').find('.task_checklist_item_name');

        $task_checklist_item_name_input.html(cache().get(`task_checklist_item_${task_checklist_item_id}`));
        cache().remove(`task_checklist_item_${task_checklist_item_id}`);

        $this.parent().addClass('uk-hidden');
        $this.prev().addClass('disabled');
    });

    // when clicking on save task checklist item name element, the system will be checking whether or not the button
    // that the user ha clicked on, ha the class of 'disabled' and if it does, this specific logic set will return
    // absolutely nothing, however, if the element does not have the disabled class, assuming that the system is
    // ready to send the request, the checklist item name will have been updated in the backend, and then the system
    // will spit back an alert to the users to let them know that the action has been completed.
    $body.on('click', '.save_task_checklist_item_name', function (event) {
        let $this = $(this),
            $task = $('#task'),
            task_id = $task.data('task_id'),
            project_id = $task.data('project_id'),
            task_checklist_item_id = $this.closest('.task_checklist_item').data('task_checklist_item_id'),
            edit_task_checklist_item_url = $('.task_checklists_wrapper').data('edit_task_checklist_item_url'),
            task_checklist_id = $this.closest('.task_checklist').attr('data-task_checklist_id');

        if (! $this.hasClass('disabled')) {
            $.ajax({
                method: 'post',
                url: edit_task_checklist_item_url,
                data: {
                    task_id: task_id,
                    project_id: project_id,
                    task_checklist_id: task_checklist_id,
                    task_checklist_item_id: task_checklist_item_id,
                    name: $this.parent().prev().html().trim()
                },
                success: function (data) {
                    ajax_message_helper($('.ajax_message_helper'), data);
                    cache().remove(`task_checklist_item_${task_checklist_item_id}`);
                    $this.addClass('disabled');
                    $this.parent().addClass('uk-hidden');
                    view_task_logs();
                }
            });
        }
    });

    // on the change of the task_checklist_item_checkbox, checked unchecked, this will fire a request to the database
    // for the particular checklist item in question. upon the checklist item being checked, this will fire an alert
    // back to the user to  state that the item has been updated.
    $body.on('change', '.task_checklist_item_checkbox', function (event) {
        let $this = $(this),
            $task = $('#task'),
            task_id = $task.data('task_id'),
            project_id = $task.data('project_id'),
            $task_checklist_item = $this.closest('.task_checklist_item'),
            task_checklist_item_id = $task_checklist_item.data('task_checklist_item_id'),
            edit_task_checklist_item_url = $('.task_checklists_wrapper').data('edit_task_checklist_item_url'),
            is_checked = $this.prop('checked'),
            $checklist = $this.closest('.task_checklist'),
            task_checklist_id = $this.closest('.task_checklist').data('task_checklist_id');

        $task_checklist_item.toggleClass('complete');

        $.ajax({
            method: 'post',
            url: edit_task_checklist_item_url,
            data: {
                task_checklist_id: task_checklist_id,
                task_checklist_item_id: task_checklist_item_id,
                is_checked: is_checked,
                task_id: task_id,
                project_id: project_id
            },
            success: function (data) {
                // recalibrating the checklist item progress bar based on the checklist items that are currently
                // sat within this group.
                recalibrate_task_checklist_progress($checklist);
                view_task_logs();

                // show some update to the user and let them know that something had happened, or even potentially
                // something that might have erroed in the backend.
                ajax_message_helper($('.ajax_message_helper'), data);
            }
        });
    });

    // this element, which will be found within the checklist item dropdown wrapper, once clicking will send a request
    // in order for deleting this particular checklist item, we are simply going to remove the element row, and alert
    // the user that the element has been deleted in the backend, rather than refreshing the elements, alleviating the
    // need for another big request. (potentially).
    $body.on('click', '.delete_task_checklist_item', function (event) {
        let $this = $(this),
            $task = $('#task'),
            task_id = $task.data('task_id'),
            project_id = $task.data('project_id'),
            task_checklist_item_id = $this.closest('.task_checklist_item').data('task_checklist_item_id'),
            delete_task_checklist_item_url = $('.task_checklists_wrapper').data('delete_task_checklist_item_url'),
            $checklist = $this.closest('.task_checklist');

        $.ajax({
            method:  'post',
            url: delete_task_checklist_item_url,
            data: {
                task_id: task_id,
                project_id: project_id,
                task_checklist_item_id: task_checklist_item_id
            },
            success: function (data) {
                ajax_message_helper($('.ajax_message_helper'), data);
                $this.closest('.task_checklist_item').remove();
                // recalibrating the checklist item progress bar based on the checklist items that are currently
                // sat within this group.
                recalibrate_task_checklist_progress($checklist);
                view_task_logs();
            }
        });
    });

    // on the stop moving of the uk-sortable element attached to the task_checklist_items element, we are going to
    // re-calibrate the ordering of the task checklist items inside; with this in mind, when the user stops moving the
    // checklist items, we will send a request, which will be calculated via javascript, and send an array of items with
    // a matching task_checklist_item_id: order_number, in array format.
    // the system will give the user some feedback that the items has been re-ordered after the request has been made.
    // the format will be [1 => 1, 2 => 2, 3 => 3]
    // if the user has opted to move a checklist item from another checklist group, then the task_checklist_id will be
    // referenced also so that the item will be accordingly ordered into it's correct checklist group on the next refresh
    $body.on('stop.uk.sortable', '.task_checklist_items', function (item) {
        let $this = $(item.detail[1]),
            $task = $('#task'),
            task_id = $task.data('task_id'),
            project_id = $task.data('project_id'),
            $task_checklist_items = $this.closest('.task_checklist_items').find('.task_checklist_item'),
            edit_task_checklist_item_order_url = $('.task_checklists_wrapper').data('edit_task_checklist_item_order_url'),
            task_checklist_item_order = [],
            task_checklist_id = $this.closest('.task_checklist').attr('data-task_checklist_id');

        $task_checklist_items.each(function (key, item) {
            var $item = $(item),
                task_checklist_item_id = $item.attr('data-task_checklist_item_id');

            task_checklist_item_order.push({
                order: (key + 1),
                task_checklist_item_id: task_checklist_item_id,
                task_checklist_id: task_checklist_id,
                task_id: task_id,
                project_id: project_id
            });
        });

        $.ajax({
            method: 'post',
            url: edit_task_checklist_item_order_url,
            data: { task_checklist_items: task_checklist_item_order },
            success: function (data) {
                ajax_message_helper($('.ajax_message_helper'), data);
            }
        });

        // recalibrating the new checklist group - when moving something from one checklist group to another, we are
        // going to need to find out what the current progress of the checklist is... so this will recalibrate it.
        recalibrate_task_checklist_progress($this.closest('.task_checklist'));

        // recalibrating the old checklist item group (if we have moved a completed/uncompleted one from this we are
        // going to need to know what the progress is of the current checklist... so this will recalibrate it.
        recalibrate_task_checklist_progress($(item.originalEvent.target.closest('.task_checklist')));
    });
});

/**
* this function is entirely used for grabbing the task checklists in their entirety, these will be loaded when the page
* loads so we are not loading in more than we need to on initial load... this will be loaded in when the document is
* ready, every time we make a new checklist, this will need to be called in order to refresh the changes so that the
* user will instantly know that something has happened.
*/
var view_task_checklists = function () {
    let $task      = $('#task'),
        task_id    = $task.data('task_id'),
        project_id = $task.data('project_id'),
        url        = $('.task_checklists_wrapper').data('view_task_checklists_url');

    $.ajax({
        method: 'get',
        url: url,
        data: {
            task_id: task_id,
            project_id: project_id
        },
        success: function (data) {
            $('.task_checklists_wrapper').html(data);
            $('.reload.view_task_checklists').find('i').removeClass('fa-spin');
        }
    });
};

/**
* This function is entirely used for when you are opting to add in checklist items, rather than refresh the entire
* checklist group with their checklist items just to load in one checklist item that was created, we refresh the
* checklist group's items... so we can see the change that had happened to that particular checklist group,
* this optimises the execution to some degree, and also gives us the ability to load in checklist items singularly rather
* than everything.
*
* @param task_checklist_id (integer)
*/
var view_task_checklist_items = function (task_checklist_id) {
    let $task_checklists_wrapper = $('.task_checklists_wrapper'),
        $task = $('#task'),
        project_id = $task.attr('data-project_id'),
        task_id = $task.attr('data-task_id'),
        view_task_checklist_items_url = $task_checklists_wrapper.data('view_task_checklist_items_url');

    $.ajax({
        method: 'get',
        url: view_task_checklist_items_url,
        data: {
            task_checklist_id: task_checklist_id,
            project_id: project_id,
            task_id: task_id
        },
        success: function (data) {
            $(`.task_checklist[data-task_checklist_id="${task_checklist_id}"]`)
                .find('.task_checklist_items')
                    .html(data);
        }
    });
};

/**
* When the user is opting to click on (check) checklist items, delete checklist item, or add new checklist item, we are
* going to need to recalibrate the percentage on the fly, rather than bringing back everything that's necessary for
* this instance.
* The checklist wrapper will be passed so we can take a direct count of everything inside and then take a count of what
* has been checked, as well as what isn't checked to be able to recount the overall completed percentage.
*
* @param $checklist
*/
var recalibrate_task_checklist_progress = function ($checklist) {
    let checklist_items_total = $checklist.find('.task_checklist_item_checkbox').length,
        checklist_items_done_total = $checklist.find('.task_checklist_item_checkbox:checked').length,
        checklist_item_width_percent = parseInt((checklist_items_done_total / checklist_items_total) * 100) + '%';

    if (checklist_items_total === 0 || checklist_items_done_total === 0)
        checklist_item_width_percent = '0%';

    $checklist.find('.progress_percent').css({
        width: checklist_item_width_percent
    });
};