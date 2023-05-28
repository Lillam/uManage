$(() => {
    // on the document load, this is going to run the view projects, which will find all of the .project elements on
    // the page and then throw them into the html so that the user in question will then be able to interact with the
    // projects of their choosing.
    view_projects();
});

/**
* The method of which is going to be acquiring all of the projects that are in the system, and return the content into
* a project wrapper with the class of .projects... the projects content will be returned with a view rendered html
* segment that gets injected in.
*
* @return html injection
*/
const view_projects = function () {
    let $projectsWrapper = $('.projects'),
        viewProjectsUrl = $projectsWrapper.data('view_projects_url');

    $.ajax({
        method: 'get',
        url: viewProjectsUrl,
        data: {
            title: $projectsWrapper.data('title'),
            view_mode: $projectsWrapper.data('view_mode')
        },
        success: (data) => $projectsWrapper.html(data)
    });
};