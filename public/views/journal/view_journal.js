$(() => {
    let $body = $("body");

    // when the user is either clicking on any of the editing areas, we are going to be turning the element into a
    // summernote object so that the user has free rein over editing the content of which sits inside the elements:
    // overall, highest and lowest point... (Summernote Preparation).
    $body.on("click", ".overall, .highest_point, .lowest_point", function (event) {
        let $this = $(this),
            original_class = $this[0].classList[0],
            original_content = $this.html().trim();

        // save the content that is originally stored inside this box wrapper, so that we can reference it when the user
        // decides to walk away from the content, and we can return the content back to what it was prior to the user
        // pressing cancel, or save.
        cache().set(original_class, original_content);

        // if the box contains a placeholder, then we are going to want to get rid of the placeholder so that the user
        // can begin typing like normal without the need of a placeholder being there.
        if ($this.find(".placeholder").length >= 1) {
            $this.html("");
        }

        // show the buttons for being able to click save or cancel.
        $this.next().removeClass("uk-hidden");

        // turn this object into a summernote with the globalised summernote options which is defined inside of
        // application.js
        $this.summernote(window.summernote_options).next().find(".note-editable").placeCursorAtEnd();
    });

    // when the user has clicked on the save buttons for the above summernote objects that might have been prepared...
    // then we are going to collect the class name from the class list, and ensure we're picking the first one...
    // this will be the target that we are not only editing, but sending up to the database as the field name.
    // after the element has been fired up and saved, we are going to shut down summernote object, and return it back
    // to a default divider element; for slick feeling functionality.
    $body.on("click", ".save_overall, .save_highest_point, .save_lowest_point", function (event) {
        let $this = $(this),
            class_list = $this[0].classList,
            target_class = class_list[0].replace("save_", "");

        $(`.${target_class}`).summernote("destroy");
        $this.parent().addClass("uk-hidden");

        // we have to define this after we have finished editing, so that we can grab exactly what is currently
        // sitting within this containing element.
        let content = $this.parent().parent().find(`.${target_class}`).html();

        // edit the journal, with the target class and the content of which we have decided to edit. with the content
        // needed. (this method will be making an ajax call).
        edit_journal(target_class, content);

        // when the user has opted to save, whether they have done anything specific or not, the value inside the cache,
        // is going to want to be deleted. we no longer need to reference what it might have been and can continue
        // as normal.
        cache().remove(target_class);
    });

    // when the user clicks on cancel of the above elements, overall, highest, or lowest point then we should assume
    // that the user is no longer wishing to make any amends, in which, we are going to destroy the summernote object
    // returning the element back to its divider self, and remove any new content that would have been added to it.
    $body.on("click", ".cancel_overall, .cancel_highest_point, .cancel_lowest_point", function (event) {
        let $this = $(this),
            class_list = $this[0].classList,
            target_class = class_list[0].replace("cancel_", ""),
            $target = $(`.${target_class}`);

        $target.summernote("destroy");
        $this.parent().addClass("uk-hidden");

        $target.html(cache().get(target_class));
        cache().remove(target_class);
    });

    // this functionality is specifically for deleting a journal entry when on the page, pressing this will kick the
    // user back to the list page... whilst deleting the current day's entry. this functionality is built for those that
    // click on a day that they can't really enter on, and decide to leave it there, rather than a blank entry sit in
    // the database, they can opt to remove it from their list for a cleaner view.
    $body.on("click", ".delete_journal", () => {
        let $journal = $("#journal"),
            delete_journal_url = $journal.data("delete_journal_url"),
            journal_id = $journal.data("journal_id");

        // todo - create and implement a re-usable confirm component that will make this reuse-able simply by adding an
        //        attribute to the button. data-confirm or something or other. (I could use a confirm package however
        //        would prefer to implement something myself.
        if (window.confirm("Are you sure you want to delete this?")) {
            $.ajax({
                method: "post",
                url: delete_journal_url,
                data: {
                    journal_id: journal_id,
                },
                success: function (data) {
                    window.location.href = data.response;
                },
            });
        }
    });

    // on the journal page, the user has the option of rating their day, by clicking on 1, 2, 3, 4 or 5 stars will
    // instantly fire up to the database the rating that the user has selected, the day needs a rating, so there will
    // be no means to take the rating away from the day once selected, but can be altered when has been selected as
    // the day can possibly change for the user, and the means to alter the rating exists here.
    $body.on("click", ".journal_rating", function (event) {
        let $this = $(this),
            rating = $this.data("rating");

        $(".journal_rating").removeClass("active").removeClass("fa fa-star").addClass("far fa-star");

        for (let i = 0; i < rating + 1; i++) {
            $('.journal_rating[data-rating="' + i + '"]')
                .removeClass("far fa-star")
                .addClass("fa fa-star");
        }

        edit_journal("rating", rating);
    });

    // when the user has focused on the new journal achievement input, the button for saving will become visible; rather
    // than displaying it and wasting space for readability, when the user has focused then we give the option to save
    // the new journal achievement entry.
    $body.on("focus", ".new_journal_achievement", function (event) {
        let $this = $(this);
        $this.parent().next().removeClass("uk-hidden");
    });

    // on the keyup of the new journal achievement element, we are going to be wanting to decide whether the save button
    // should have the class of disabled or not, if the input is not empty, then the saving button becomes enabled,
    // otherwise there's no point allowing the user to click on a button when no value is currently present saving the
    // server an unnecessary request.
    $body.on("keyup", ".new_journal_achievement", function (event) {
        let $this = $(this);

        if (event.key === "Enter") {
            $(".save_new_journal_achievement").click();
            return;
        }

        if ($this.val() !== "") {
            $this.parent().parent().find(".save_new_journal_achievement").removeClass("disabled");
        } else {
            $this.parent().parent().find(".save_new_journal_achievement").addClass("disabled");
        }
    });

    // when the user walks away from the input, the system is going to need to know whether there is a value in
    // the input or not, for a reason of re-hiding the button that allows the user to save, however the system will
    // only hide the button. if the input has no value, otherwise, if the input does have a value the button for saving
    // will remain until the user decides to take further action.
    $body.on("blur", ".new_journal_achievement", function (event) {
        let $this = $(this);
        if ($this.val() !== "") {
            $this.parent().parent().find(".save_new_journal_achievement").removeClass("disabled");
        } else {
            $this.parent().parent().find(".save_new_journal_achievement").addClass("disabled");
            $this.parent().next().addClass("uk-hidden");
        }
    });

    // this button is the method that allows the saving of journal achievements via ajax; when clicking this button
    // there will be a check to see if it has the class of disabled, and if it does, it wants to return there and then
    // and not even bother making an ajax request. Otherwise, if the value is not present; then the system will go ahead
    // make the request, reload the journal achievements on the page to make sure that the visualised changes have been
    // rendered to the user; once this has happened the button will be re-assigned the value of disabled whilst the
    // input has its value removed.
    $body.on("click", ".save_new_journal_achievement", function (event) {
        let $this = $(this),
            journal_id = $("#journal").data("journal_id"),
            url = $("#journal_achievements").data("make_journal_achievements_url"),
            journal_achievement = $(".new_journal_achievement").val();

        // if this element has the class of disabled, then we are going to want to stop the trace right here and not
        // attempt to make an ajax request...
        if ($this.hasClass("disabled")) {
            return;
        }

        // assuming the above element does not have the class of disabled, then we can proceed with the ajax request...
        $.ajax({
            method: "post",
            url: url,
            data: {
                journal_id: journal_id,
                journal_achievement: journal_achievement,
            },
            success: function (data) {
                // reload the journal achievements after we have just created one.
                view_journal_achievements();

                // reset the button and form input to their default state.
                $(".new_journal_achievement").val("");
                $this.addClass("disabled");
            },
        });
    });

    // the user might want to delete an achievement for a variety of reasons, whatever the reason is unnecessary but the
    // means to be able to delete one is there, when the user clicks delete achievement button an ajax request will be
    // fired with the journal_id as well as the journal_achievement id to ensure that we are in fact going to be
    // deleting the correct journal entry at all times. then when the journal has been successfully deleted, the system
    // will spit back an ajax response that appears on screen to the user... letting them know something has happened
    // as well as reloading the journal achievements to reflect the change that has just been made.
    $body.on("click", ".delete_journal_achievement", function (event) {
        let $this = $(this),
            delete_journal_achievement_url = $("#journal_achievements").data("drop_journal_achievements_url"),
            journal_id = $("#journal").data("journal_id"),
            journal_achievement_id = $this.closest(".journal_achievement").data("journal_achievement_id");

        $.ajax({
            method: "post",
            url: delete_journal_achievement_url,
            data: {
                journal_id: journal_id,
                journal_achievement_id: journal_achievement_id,
            },
            success: function (data) {
                ajax_message_helper(data);
                view_journal_achievements();
            },
        });
    });

    // when the user is key pressing on the journal_achievement_content AKA journal_achievement.name, then we are going
    // to want to check whether enter has been pressed or not, and if the enter button gets pressed then we should
    // assume that the user has finished with editing the particular entry; then we are going to fire the change
    // through the edit_journal_achievement method, so it can build the request for updating.
    $body.on("keypress", ".journal_achievement_content", function (event) {
        let $this = $(this),
            journal_achievement_id = $this.closest(".journal_achievement").data("journal_achievement_id"),
            journal_achievement_content = $this.html().trim();

        if (event.key === "Enter") {
            event.preventDefault();
            edit_journal_achievement("name", journal_achievement_content, journal_achievement_id);
            // blur the input after we have entered, we no longer wish to be assigned to editing it after pressing enter.
            $this.blur();
            // return the method here, we don't need to allow any further functionality after the user has entered.
            return;
        }
    });

    $body.on("click", ".journal_information", function (event) {
        event.stopPropagation();

        if (window.innerWidth <= 991) {
            $body.removeClass("journal_sidebar_closed");
            $body.toggleClass("journal_sidebar_open");
        }

        if (window.innerWidth > 991) {
            $body.toggleClass("journal_sidebar_closed");
            $body.removeClass("journal_sidebar_open");
        }
    });

    $body.on("click", ".journal_sidebar", function (event) {
        event.stopPropagation();
    });

    // instantiate the journal achievements against this journal entry.
    view_journal_achievements();
});

/**
 * This method is built for grabbing all the journal achievements that happen to be on the journal entry that we are
 * looking at. This will be called on load so that the journal achievements are automatically there when the page
 * has loaded.
 *
 * @param callback (null|function)
 */
const view_journal_achievements = function (callback = null) {
    let $journal_achievements = $("#journal_achievements"),
        get_journal_achievements_url = $journal_achievements.data("get_journal_achievements_url"),
        journal_id = $("#journal").data("journal_id");

    $.ajax({
        method: "get",
        url: get_journal_achievements_url,
        data: {
            journal_id: journal_id,
        },
        success: function (data) {
            $journal_achievements.html(data);

            if (callback !== null) {
                callback();
            }
        },
    });
};

/**
 * This method is specifically for crafting the request and response to and from the server in regard to editing
 * journals. the field and value are completely variable that we are able to pass through in the code in order to
 * manipulate the journal
 *
 * @param field    (string)
 * @param value    (wildcard|variable)
 * @param callback (null|function)
 */
const edit_journal = function (field, value, callback = null) {
    let $journal = $("#journal"),
        journal_id = $journal.data("journal_id"),
        edit_journal_url = $journal.data("edit_journal_url");

    $.ajax({
        method: "post",
        url: edit_journal_url,
        data: {
            field: field,
            value: value,
            journal_id: journal_id,
        },
        success: function (data) {
            ajax_message_helper(data);

            if (callback !== null) {
                callback();
            }
        },
    });
};

/**
 * This method is specifically for crafting the request and response to and from the server in regard to editing
 * journal achievements. The field and value are completely variable, that we are able to pass through in the code in
 * order to manipulate the journal achievement
 *
 * @param field                  (string)
 * @param value                  (variable)
 * @param journal_achievement_id (integer)
 * @param callback               (null|function)
 */
const edit_journal_achievement = function (field, value, journal_achievement_id, callback = null) {
    let $journal_achievements = $("#journal_achievements"),
        edit_journal_achievement_url = $journal_achievements.data("edit_journal_achievements_url");

    $.ajax({
        method: "post",
        url: edit_journal_achievement_url,
        data: {
            field: field,
            value: value,
            journal_achievement_id: journal_achievement_id,
        },
        success: function (data) {
            ajax_message_helper(data);

            if (callback !== null) {
                callback();
            }
        },
    });
};
