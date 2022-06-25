<?php

namespace App\Http\Controllers\Web\Project;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Project\Project;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Models\Project\ProjectUserContributor;
use App\Repositories\Project\ProjectSettingRepository;

class ProjectController extends Controller
{
    /**
    * This method is for returning the projects view to the user, this will return just a simple view with all the
    * projects that the specific user in question can see.
    *
    * @return Factory|View
    */
    public function _viewProjectsGet(): Factory|View
    {
        $this->vs->set('title', '- Projects')
                 ->set('current_page', 'page.projects.list');

        return view('project.view_projects');
    }

    /**
    * @param Request $request
    * @return Factory|View
    */
    public function _ajaxViewProjectsGet(Request $request): Factory|View
    {
        $user_id = $request->input('user_id') ?? $this->vs->get('user')->id;

        $projects = Project::query()
            ->select('*')
            ->with([
                'project_setting',
                'user_contributors',
                'user_contributors.user'
            ])
            ->where('user_id', '=', $user_id)
            ->orderBy('name', 'asc')
            ->get();

        $projects = $projects->sortByDesc(function ($project) {
            return $project->project_setting->updated_at;
        });

        $view_mode = $request->input('view_mode');

        return view('library.project.ajax_view_projects', compact(
            'projects',
            'view_mode'
        ));
    }

    /**
    * This method is for returning a project specifically, we are going to be looking for a project with the passed id;
    * then we are going to check whether this user in question can see this particular project or not... if the
    * authenticated user cannot see the project the user will be sent back to the view projects page.
    *
    * @param Request $request
    * @param string $project_code
    * @return Factory|RedirectResponse|View
    */
    public function _viewProjectGet(Request $request, string $project_code): Factory|RedirectResponse|View
    {
        $project = Project::select('*')
            ->with([
                'project_setting',
                'user_contributors',
                'user_contributors.user'
            ])
            ->where('code', '=', $project_code)
            ->first();

        // if we have gone to this particular url with filters in mind, then we are going to objectify the filters
        // so that we can pass them through in one object string rather than defining a bulk amount of filter variables.
        $filters = (object) [
            'task_statuses'    => $request->input('task_status') ?? '',
            'task_issue_types' => $request->input('task_issue_type') ?? '',
            'task_priorities'  => $request->input('task_priority') ?? ''
        ];

        // if there is no project for the above query, then we can assume that the user either doesn't have the project
        // that they're requesting, or they're trying to view someone else's project that their profile doesn't have
        // access to... thus we are going to need to abort here and provide a 404...
        if (! $project instanceof Project)
            abort(404);

        // we are going to check to see if this particular user that is signed in, is able to view the project that
        // they're navigating to, if they don't then we are going to want to abort 403... telling the user that they
        // don't have the necessary permissions to do this.
        if ($this->vs->get('user')->cannot('ProjectPolicy@viewProject', $project))
            abort(403);

        $this->vs->set('title', " - Project - {$project->name}")
                 ->set('current_page', 'page.projects.list');

        return view('project.view_project', compact(
            'project',
            'filters'
        ));
    }

    /**
    * This method is entirely for showing the visuals for creating projects, this method will simply be returning a
    * modal view, which will be instantly injected into the body, and upon being injected into the body, will then
    * be rendered on the page; this will load up all the necessary fields for a project to be made...
    *
    * @param Request $request
    * @return Factory|View
    */
    public function _ajaxViewCreateProjectGet(Request $request): Factory|View
    {
        return view('project.modals.view_make_project_modal');
    }

    /**
    * This method is entirely for creating projects, this will be requiring the necessities for what a project is needed
    * otherwise the values will be entered as null. The only things that are necessary from a passed reference is the
    * name of the project.
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _ajaxCreateProjectPost(Request $request): JsonResponse
    {
        $project_id = Project::create([
            'user_id'     => $user_id = $this->vs->get('user')->id,
            'name'        => $request->input('name'),
            'code'        => $request->input('code'),
            'description' => $request->input('description'),
            'icon'        => $request->input('icon') ?? 'fa fa-edit',
            'color'       => $request->input('color') ?? 'ffa500'
        ])->id;

        // once the project has been created... create the project settings.
        ProjectSettingRepository::createProjectSettingUponProjectCreation($project_id);

        // when we have created the project, we want to automatically assign the user who created it, as a project
        // contributor (this wants to also be editable on the project setting page).
        ProjectUserContributor::create(['project_id' => $project_id, 'user_id' => $user_id]);

        return response()->json(['response' => 'project successfully created']);
    }

    /**
    * This method is as simply as it sounds, this method will be utilised for deleting the project itself, when the
    * user clicks on a 'delete_project' button that goes to this url, the passed parameter is the project id in
    * question, and will just return the user back to looking at projects. this will also check whether or not the
    * authenticated user has permission for deleting this particular project in question.
    *
    * @param Request $request
    * @param integer $project_id
    * @return RedirectResponse
    */
    public function _deleteProjectGet(Request $request, $project_id): RedirectResponse
    {
        $project = Project::where('id', '=', $project_id)
            ->where('user_id', '=', $this->vs->get('user')->id)
            ->first();

        // if we don't have a project that we're working with, then we are going to abort the system and return a 404..
        // this will alert the user that the project in question does not exist... meaning they're trying to access
        // a project that isn't even in the system (potentially random url string)
        if (! $project instanceof Project)
            abort(404);

        // if the user does not have the permission to delete the project, then we are going to abort with a 403 and
        // show a permission error, otherwise, if they do have the permission, ignore this set of logic and proceed
        // with the rest of the code-set.
        if ($this->vs->get('user')->cannot('ProjectPolicy@deleteProject', $project))
            abort(403);

        // delete the actual project that we're looking at
        $project->delete();

        // return a redirect back to the view projects, rather than starting at the settings of a project that no longer
        // exists.
        return redirect()->action('Project\ProjectController@_viewProjectsGet');
    }
}
