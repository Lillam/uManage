<?php

namespace App\Action\Project;

use App\Action\Action;
use App\Models\Project\Project;
use Illuminate\Support\Facades\Auth;

class CreateProjectAction extends Action
{
    public function handle(): void
    {
        Project::query()->create([
            'user_id'     => Auth::id(),
            'name'        => $this->request->input('name'),
            'code'        => $this->request->input('code'),
            'description' => $this->request->input('description'),
            'icon'        => $this->request->input('icon') ?? 'fa fa-edit',
            'color'       => $this->request->input('color') ?? 'ffa500'
        ]);
    }
}