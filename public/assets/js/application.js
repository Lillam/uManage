/**
* @type {{toolbar: *[][]}}
* we are setting a global default for the summernote objects that we are going to be working with around the system
* anywhere that requires a summernote will be utilising these options so that we have a sense of standardisation and
* not re-using and recreating the same set of options on every page that is requiring them.
*/
window.summernote_options = {
    toolbar: [
        ['style', ['bold', 'clear']],
        ['font', ['strikethrough']],
        ['para', ['ul', 'ol']],
        ['view', ['codeview', 'codeBlock']],
        ['insert', ['link']]
    ],
    buttons: {
        codeBlock: function (context) {
            return context.ui.button({
                contents: '<i class="fa fa-code"></i>',
                click: function (event) {
                    $(event.target)
                        .closest('.note-editor')
                        .find('.note-editable > p:last-of-type').find('br').remove();
                    $(event.target)
                        .closest('.note-editor')
                        .find('.note-editable > p:last-of-type').append('<pre><code>...</code></pre>');
                }
            }).render();
        }
    },
    followingToolbar: false,
    hint: {
        match: /:([\-+\w]+)$/,
        search: function (keyword, callback) {
            callback($.grep(window.emojis, function (item) {
                return item.indexOf(keyword) == 0;
            }));
        },
        template: (item) => `${window.emoji_items[item]} :${item}:`,
        content: (item) => window.emoji_items[item]
    }
};

$(() => {
    // Setting up ajax with the necessary headers so that no matter where im making the ajax call im not going to need
    // to pass in the csrf token every time, this makes ajax-ing everywhere much easier to deal with.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // defining some global variables that will be used throughout any page that is including this...
    let $html = $('html'),
        $body = $('body');

    // when the user has opted to click on the html page, and it doesn't really matter where they have clicked
    // then we are going to attempt to remove the open class, from all sidebars... so that they will be then re-hidden
    // this is a makeshift blur for the sidebar...
    $html.on('click', function (event) {
        // $('.open').removeClass('open');
        ['open', 'accounts_sidebar_open', 'journal_sidebar_open'].forEach((itemClass) => {
            $(`.${itemClass}`).removeClass(itemClass);
        })
    });

    // when wea re dealing with ajax requests... there is going to need to be a method of which allows me to close the
    // alert... this will pretty much simply just remove the 'active' class from the ajax message helper container...
    // which will just display none on the message and then when we call this again, the new html will be inserted
    // and the element will be updated with the new html...
    $body.on('click', '.ajax_message_helper_close', function (event) {
        event.preventDefault();
        $('.ajax_message_helper').html('').removeClass('active');
    });

    /**
    * Dropdown functionality.
    */
    $body.on('click', '.dropdown_button', function (event) {
        event.stopPropagation();
        let $this = $(this);
        $this.next().toggleClass('open');
    });

    // when clicking on any element inside the dropdown then we are going to just bubbling up and causing the
    // dropdown closing on itself, meaning that the user can click on elements inside the dropdown.
    $body.on('click', '.dropdown', function (event) {
        event.stopPropagation();
    });

    // when the user has opted to click on save new project, this will run the logic through the (create project) method
    // which will handle the checking whether the details required to create a project has been filled or not, handling
    // the responses in kind depending on what might be missing or yet needed.
    $body.on('click', '.save_new_project', function (event) {
        event.stopPropagation();
        event.preventDefault();
        create_project();
    });

    // when the user has opted to click on save new task, this will run the logic through the (create task) method
    // which will handle all the checking whether the details required for creating a new task has been filled or not,
    // handling the responses in kind depending on what might be missing or yet needed.
    $body.on('click', '.save_new_task', function (event) {
        event.stopPropagation();
        event.preventDefault();
        create_task();
    });

    $body.on('click', '.user', function (event) {
        event.stopPropagation();
        let $this = $(this);
        $this.closest('.user-wrapper').toggleClass('open');
    });

    // method for creating a task, when the user clicks on the anchor tag that has the attribute "create-task" we are
    // going to want to make an ajax request to the server... once we have done this, we're essentially going to bring
    // back some modal content, and dump it onto the page... and then call uikit.modal to open it up and display
    // the form that will allow the user to create a new project.
    $body.on('click', 'a[create-task]', function (event) {
        event.preventDefault();

        let $this = $(this),
            url = $this.attr('href');

        $.ajax({
            method: 'get',
            url: url,
            data: {},
            success: (data) => {
                $('.make_task_modal').remove();
                $('body').append(data);
                UIkit.modal('.make_task_modal').show();
                $('.make_task_description').summernote(window.summernote_options);
            }
        })
    });

    // method for creating a project, when the user clicks on the anchor tag that has the attribute "create-project" we
    // are going to want to make an ajax request to the server... once we have done this, we're essentially going to
    // bring back some modal content, and dump it onto the page... and then call uikit.modal to open it up and display
    // the form that will allow the user to create a new project...
    $body.on('click', 'a[create-project]', function (event) {
        event.preventDefault();

        let $this = $(this),
            url = $this.attr('href');

        $.ajax({
            method: 'get',
            url: url,
            data: {},
            success: (data) => {
                $('.make_project_modal').remove();
                $('body').append(data);
                UIkit.modal('.make_project_modal').show();
            }
        })
    });

    // If the user is in desire of making some more space for their viewing they are going to be able to collapse the
    // sidebar; should they do this we're going to make an ajax request to the server in order to confirm this action
    // so that on their next refresh the website will be remembered and the sidebar will either be hidden or visible
    // entirely depending on the users' preference.
    $body.on('click', '.close-sidebar', () => {
        let url = $body.data('collapse_sidebar_url'),
            collapsed = $body.hasClass('sidebar-closed');

        // this functionality on wants to happen when the screen is greater >= 800; otherwise this will be hidden on
        // mobile and the functionality will no longer be "state" dependant. and if we're on a screen size of <= 799
        // then this can be ignored.
        if (window.innerWidth > 800) {
            $.ajax({
                method: 'get',
                url: url,
                data: {
                    is_collapsed: !! collapsed ? 0 : 1
                },
                success: () => {
                    $body.toggleClass('sidebar-closed');
                }
            });

            $body.removeClass('sidebar-open');
            return;
        }

        $body.toggleClass('sidebar-open');
    });
});

// methods for extending query... anything that extends jquery in any specific way will be implemented right here in this
// specific stack of code... $.fn.extend just appends methods onto jquery methods, so if we have $('element').something
// .something is the extension that we're adding to the particular project...
$.fn.extend({
    /**
    * Override function for jquery, which will allow us to place the cursor at the end of an element, this will just
    * place the | flashing cursor and focus to the end of a string, of an input of choice...
    *
    * @returns {placeCursorAtEnd}
    */
    placeCursorAtEnd: function() {
        // Places the cursor at the end of a content-editable container (should also work for textarea / input)
        if (this.length === 0) {
            throw new Error("Cannot manipulate an element if there is no element!");
        }

        let element = this[0],
            range = document.createRange(),
            selection = window.getSelection(),
            childLength = element.childNodes.length;

        if (childLength > 0) {
            let lastNode = element.childNodes[childLength - 1],
                lastNodeChildren = lastNode.childNodes.length;

            range.setStart(lastNode, lastNodeChildren);
            range.collapse(true);
            selection.removeAllRanges();
            selection.addRange(range);
        }

        return this;
    }
});

/**
* This method is entirely for handling the frontend visuals when we are dealing with responses that come back from ajax.
* this is a prettified alert that will appear on screen when the user has made some changes that did not require a page
* refresh, this method will give the user some feedback that some changes have been made. this is useful for instant
* feedback to the user.
*
* @param $object | Where are we injecting the data to.
* @param data    | The data of which we are injecting into Object.
*/
let ajax_message_helper = function ($object, data) {
    let html = data.response;
        html += '<a class="ajax_message_helper_close"><i class="fa fa-close"></i></a>';

    $object.html(html);
    $object.addClass('active');
    setTimeout(function() {
        $object.removeClass('active');
    }, 1500);
};

/**
* when the user begins to open a summernote object, we are going to check to see if there is a placeholder element inside
* and set its value to nothing, we are going to delete the object in question from memory, store its value in the
* cache, so that once we are done editing or messing with this instance, if nothing has changed then we set the value
* back to what the cache value was.
*
* @param key   | The key of which the value will be stored against, subsequently, this will also want to be the class or
*              | Identifier of the element.
* @param value | the value for the key that is being stored.
* @return void
*/
const handle_summernote_open = function (key, value) {
    let $object = $('.' + key);
    if ($object.find('.placeholder').length >= 1) {
        $object.html('');
    }
    window[key] = value;
    // unset the object, we no longer need it.
    delete $object;
};

/**
* This method will be doing the exact opposite of what the handling of the summernote open does, however this will be
* undoing everything and removing the value from cache, after it has applied that cache to the element in question's
* placeholder element, to simulate that nothing has been changed, and that it is now ready for editing again.
*
* @param $object        | jquery object
* @param handle         | point of reference (save or cancel)
* @param key            | the identifier for the content should the content have been saved into the system.
* @param update_on_save | whether we're opting to update the element when we have left the summernote leave...
*/
const handle_summernote_leave = function ($object, handle, key, update_on_save = true) {
    // regardless of whether we are saving or cancelling, we are wanting to reset the value of the object
    // back to its original value... on the exit of the summernote, the object will be set to the previous value
    // and the cache key will be set to nothing.
    if (update_on_save) {
        $object.html(window[key]);
    }
    delete window[key];
    delete $object;
};

/**
* Cache functionality.
*/

/**
* This method will be acquiring elements from cache that we have stored, cache_get('storage_key'); will return whatever
* has been stored within the storage_key value. everything will have been stored within the window object.
*
* @param key
* @return {*}
*/
let cache_get = function (key) {
    return window[key];
};

/**
* This method will be saving a value into a key identifier that is set by the developer, if we are opting to save a
* particular value, then we are going to need the key as well as the value. cache_save('key', 1);
* cache_get('key') will print 1, all this method does is storing the value into the window object.
*
* @param key
* @param value
* @return void
*/
let cache_save = function (key, value) {
    window[key] = value;
};

/**
* We are going to need a method for deleting the cache objects when we are no longer needing to store the information
* there's no sense in storing a mass amount of data into the window object, so this method will simply unset and delete
* the cache key so that we are no longer storing that value anymore. as referencing the above method:
* cache_delete('key'); and cache_get('key') will output undefined.
*
* @param key
* @return void
*/
let cache_delete = function (key) {
    delete window[key];
};

/**
* Method for creating a project, if the user clicks on the create project button, this method will take care of all the
* processing for handling the details, if there are any details that haven't been filled then the system will handle it
* here, otherwise, this will collect all the data we need to push up to the database and then query from that.
*
* @return void
*/
var create_project = function () {
    let $make_project_modal  = $('.make_project_modal'),
        make_project_url     = $make_project_modal.data('make_project_url'),
        $project_name        = $make_project_modal.find('.make_project_name'),
        $project_description = $make_project_modal.find('.make_project_description'),
        $project_code        = $make_project_modal.find('.make_project_code'),
        $project_color       = $make_project_modal.find('.make_project_color'),
        $project_icon        = $make_project_modal.find('.make_project_icon');

    $.ajax({
        method: 'post',
        url: make_project_url,
        data: {
            name: $project_name.val(),
            description: $project_description.val(),
            code: $project_code.val(),
            color: $project_color.val().replace('#', ''),
            icon: $project_icon.val()
        },
        success: function (data) {
            // if we are on a page that has the function of (view_projects) defined then we are going to want to
            // call it so that the projects are showing the up-to-date version of all projects that are in the system
            // ready for this particular user.
            if (typeof (view_projects) !== "undefined") {
                view_projects();
            }

            // we no longer need the utilisation of this dom element, so we might as well just remove it from the browser
            // no sense in retaining this information, and if we ever need it back, we can make another request to get
            // it back, rather than display it again.
            $make_project_modal.remove();
        }
    });
};

/**
* Method for creating a task, if the user clicks on the create task button, this method will take care of the processing
* for handling the details. If there are any details that haven't been filled then the system will handle it here.
* otherwise, this will collect all teh data that we are needing to push up to the database and query from that.
*/
var create_task = function () {
    let $make_task_modal = $('.make_task_modal'),
        make_task_url = $make_task_modal.data('make_task_url'),
        $task_name = $make_task_modal.find('.make_task_name'),
        $task_description = $make_task_modal.find('.make_task_description').next().find('.note-editable'),
        $task_project_id = $make_task_modal.find('.make_task_project_id');

    $.ajax({
        method: 'post',
        url: make_task_url,
        data: {
            name: $task_name.val(),
            description: $task_description.html(),
            project_id: $task_project_id.val(),
        },
        success: function (data) {
            if (typeof (view_projects) !== "undefined") {
                view_projects();
            }

            if (typeof (view_tasks) !== "undefined") {
                view_tasks();
            }

            // if we have managed to successfully create a task; then we are going to simply remove the modal from
            // the dom, we are no longer needing the html stack, and if we want to create another task, we are going to
            // simply ajax another modal back in.
            $make_task_modal.remove();
        }
    });
};

/**
* This method is dedicated for setting up charts on a more dynamic way, without having to continuously setting up a
* statement of new chart here and there when we can just simply pass in the element target, this is going to replace
* and reduce the amount of code around the system (the need for duplicated setup methods of the charts)
*
* This construct of code is in need of chart.js which can be found at: https://www.chartjs.org/docs
*
* @param chart_element
* @param chart_type
* @param chart_data
*/
const setup_chart = function (chart_element, chart_type, chart_data) {
    if (window[`${chart_element}_graph`]) {
        window[`${chart_element}_graph`].destroy();
    }

    let chart_options = {
        type: chart_type,
        data: chart_data,
    };

    // if the graph type, is bar, then we are always going to want to start at 0...
    if (chart_type === 'bar') {
        chart_options.options = {
            scales: {
                yAxes: [{ ticks: { beginAtZero: true, min: 0 }}]
            }
        };
    }

    // assign this graph to a variable, which will be checked when the data is refreshed, and if it exists, this will
    // get purged and re-assigned to a variable; with the new data in question.
    window[`${chart_element}_graph`] = new Chart(
        $(`.${chart_element}`),
        chart_options
    );
};

/**
* A method that is going to be running off to acquire all the emojis for the web page... and if we already have them
* loaded, then we aren't going to bother trying to acquire them again; only load them when we are going to be utilising
* them... otherwise, this is going to be a constant and continuously requested piece of code when it isn't really needed
* all the time.
*
* Method of making this better - we could add the result into a local store cache against the user's machine to stop
* this from making more than one request every time the page loads...
*
* @return void
*/
const load_emojis = function () {
    if (typeof(window.emojis) === "undefined" || typeof(window.emoji_items) === "undefined") {
        $.ajax({
            method: 'get',
            url: $('body').data('get_emojis_url'),
        }).then((data) => {
            window.emojis = Object.keys(data);
            window.emoji_items = data;
        });
    }
};

load_emojis();