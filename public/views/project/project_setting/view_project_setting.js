$(() => {
    let $body = $("body");

    $body.on("change", ".project_color", function (event) {
        let $project_color_options = $(".project_color_options");
        $project_color_options.removeClass("uk-hidden");
    });

    $body.on("click", ".save_project_color, .cancel_project_color", function (event) {
        var $this = $(this),
            $project_color_options = $(".project_color_options");

        if ($this.hasClass("save_project_color")) {
            update_project("color", $(".project_color").val().replace("#", ""));
        }

        $project_color_options.addClass("uk-hidden");
    });
});

/**
 *
 * @param field
 * @param value
 */
var update_project = function (field, value) {
    var $project = $(".project"),
        project_id = $project.find('input[name="project_id"]').val(),
        update_project_url = $project.data("update_project_url");

    $.ajax({
        method: "post",
        url: update_project_url,
        data: {
            project_id: project_id,
            field: field,
            value: value,
        },
        success: function (data) {
            ajax_message_helper(data);
        },
    });
};
