$(() => {
    let $body = $("body");

    // a method for being able to refresh the box segment, this method will allow the user to just click and have the
    // comment segment just refresh (this will be in case that they're wanting to make sure that their visuals are
    // currently up to date with what someone else might be working on).
    $body.on("click", ".reload.view_task_comments", function (event) {
        let $this = $(this);
        $this.find("i").addClass("fa-spin");
        view_task_comments();
    });

    // when the user clicks on the comment wrapper, we are going to be firing an event which turns the wrapper in question
    // into a summernote wysiwyg.
    $body.on("click", ".comment", function (event) {
        let $this = $(this);
        handleSummernoteOpen("comment", $this.html());
        $this.parent().find(".comment_options").removeClass("uk-hidden");
        // when clicking on the comment segment, we are going to want to place the cursor at the end of the content...
        // there shouldn't be any content to begin with however, this is a form of standardisation across the board.
        $this.summernote(window.summernote_options).next().find(".note-editable").placeCursorAtEnd();
    });

    // handle the processing for saving and cancelling comments, this particular segment of code is 100% devoted to
    // handling the processing of comment logic, if you click cancel, it will process and handle the necesasry set
    // logic, if you click save then it will proceed to continue with further code logic in order for showing that
    // the changes has been made.
    $body.on("click", ".save_comment, .cancel_comment", function (event) {
        event.preventDefault();
        let $this = $(this),
            $comment = $(".comment"),
            handle = $this.hasClass("cancel") ? "cancel" : "save";

        $this.parent().addClass("uk-hidden");

        $comment.summernote("destroy");

        if (handle === "save") {
            save_new_task_comment($comment.html());
        }

        // if the element has the class of cancel, then we are simply going to want to pass in the summernote object
        // so that we are able to do something particular with it, and revert back to what the content once used to be
        // rather than viewing what the content is currently set to be.
        handleSummernoteLeave($comment, handle, "comment");
    });

    // when the user clicks on edit or delete comment. then we are going to decide which action to take, if the user has
    // clicked on delete the comment, then we are simply going to fire an ajax call which deletes the comment, otherwise
    // we are going to find the original comment and then turn the found wrapper into a wysiwyg so that the user is able
    // to edit the comment in question.
    $body.on("click", ".edit_comment, .delete_comment", function (event) {
        let $this = $(this),
            $comment_item = $this.closest(".comment_item"),
            task_comment_id = $comment_item.data("task_comment_id");

        if ($this.hasClass("delete_comment")) {
            delete_task_comment(task_comment_id);
        }

        if ($this.hasClass("edit_comment")) {
            let $task_comment_content = $comment_item.find(".task_comment_content");
            $task_comment_content.summernote(window.summernote_options);
        }
    });

    $body.on("click", ".task_comments .paginate_previous, .task_comments .paginate_next", function (event) {
        let $this = $(this),
            direction = $this[0].classList[0];
        view_task_comments(direction.split("paginate_")[1]);
    });
});

/**
 * This will update the comments html box, with the new comments information, this will take a parameter of direction
 * which will be a case of checking whether or not  we are  looking for things paginated prior to t his,  or paginated
 * after this... and returning the select amount of results back to the user.
 *
 * @param direction
 * @return
 */
var view_task_comments = function (direction = false) {
    let $task_comments = $(".task_comments"),
        $task = $("#task"),
        task_id = $task.data("task_id"),
        project_id = $task.data("project_id"),
        url = $task_comments.data("view_task_comments_url"),
        page = parseInt($task_comments.attr("data-page"));

    if (direction === "previous" && page !== 0) page -= 1;
    if (direction === "next") page += 1;

    $task_comments.attr("data-page", page);

    $.ajax({
        method: "get",
        url: url,
        data: {
            task_id: task_id,
            project_id: project_id,
            page: page,
        },
        success: function (data) {
            $task_comments.html(data.html);
            $(".reload.view_task_comments").find("i").removeClass("fa-spin");
        },
    });

    // request().get(url, { task_id, project_id, page })
    //     .then((data) => {
    //         $task_comments.html(data.html);
    //         $('.reload.view_task_comments').find('i').removeClass('fa-spin');
    //     });
};

/**
 * Method which will handle the ajaxing of taking the task id and the content for the commment so that we can make a
 * request to the TaskCommentController for the system to be able to create the new task. on the creation of the task
 * comment we will be re-loading the task comments so that the new comment will be present.
 *
 * @param content
 */
var save_new_task_comment = function (content) {
    let url = $(".new_comment").data("make_task_comment_url"),
        task_id = $("#task").data("task_id"),
        project_id = $("#task").data("project_id");

    $.ajax({
        method: "post",
        url: url,
        data: {
            task_id: task_id,
            project_id: project_id,
            content: content,
        },
        success: function (data) {
            ajax_message_helper(data);
            view_task_comments();
        },
    });
};

/**
 * This particular method will take the parameter of a task_comment_id (the one of which we are aiming to delete, and then
 * push it through to the ajax call, which will delete this particular entry from the database and then refresh the
 * comment section.
 *
 * @param task_comment_id (integer)
 */
var delete_task_comment = function (task_comment_id) {
    let url = $(".task_comments").data("delete_task_comment_url");
    $.ajax({
        method: "post",
        url: url,
        data: { task_comment_id: task_comment_id },
        success: function (data) {
            ajax_message_helper(data);
            // get theh task comment that we have just opted to delete, and remove it from the document... so that the
            // change can be instantly noted, without the need for having to refresh the segment making some unncessary
            // queries...
            $(`.comment_item[data-task_comment_id="${task_comment_id}"]`).remove();
        },
    });
};
