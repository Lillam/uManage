<?php

namespace App\Http\Controllers\Api\Project;

use Illuminate\Http\Request;
use App\Models\Project\Project;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\Controller;
use App\Action\Project\CreateProjectAction;

class ProjectController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function view(Request $request): JsonResponse
    {
        return $this->respond(Project::all());
    }

    public function create(CreateProjectAction $action): JsonResponse
    {
        try {
            $action->handle();

            return $this->respond([ 'success' => 'Project was created' ]);
        }

        catch (\Throwable $exception) {
            return $this->respond([ 'error' => $exception->getMessage() ]);
        }
    }
}