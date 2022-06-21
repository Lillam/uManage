<?php

namespace App\Http\Controllers\Project;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Project\Project;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Project\ProjectSetting;
use Illuminate\Contracts\View\Factory;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

class ProjectSettingController extends Controller
{
    /**
     * @return Factory|View
     */
    public function _viewProjectsSettingsGet(): Factory|View
    {
        $this->vs->set('current_page', 'page.projects.settings')
                 ->set('title', '- Projects - Settings');

        return view('project.project_setting.view_projects_settings');
    }

    /**
    * @param $project_code
    * @return Factory|View
    */
    public function _viewProjectSettingsGet($project_code): Factory|View
    {
        $project_setting = ProjectSetting::query()
            ->select('project_setting.*')
            ->with('project')
            ->leftJoin('project', 'project.id', '=', 'project_setting.project_id')
            ->where('project.code', '=', $project_code)
            ->first();

        // quick check to see if the project setting is an instance of the project setting model, and if it is not...
        // then we are trying to look at a project that doesn't yet exist, and here we are simply going to throw a 404
        // then the user can then decide what they're going to do next.
        if (! $project_setting instanceof ProjectSetting)
            abort(404);

        $this->vs->set('title', "- Project Settings - {$project_setting->project->name}")
                 ->set('current_page', 'page.projects');

        return view('project.project_setting.view_project_settings', compact(
            'project_setting'
        ));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function _editProjectSettingsPost(Request $request): JsonResponse
    {
        $project_id = $request->input('project_id');
        $field      = $request->input('field');
        $value      = $request->input('value');

        $project = Project::where('id', '=', $project_id)->first();

        if ($this->vs->get('user')->cannot('ProjectPolicy@editProject', $project))
            return response()->json(['error' => 'You are unable to edit this particular project']);

        $project->$field = $value;
        $project->save();

        return response()->json([ 'success' => 'You have successfully edited this project' ]);
    }
}
