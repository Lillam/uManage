<?php

namespace App\Repositories\Project;

use App\Models\Project\Project;

class ProjectRepository
{
    /**
    * This method is just going to be taking a user identifier, and upon the creation of a new project, it's just
    * going to be dumping that identifier back; and incrementing the id that way; which would make it possible to have
    * user_id 1: project 1. user_id 2: project 1... rather than having an obscure sense of identifiers.
    *
    * @param int $user_id
    * @return int
    */
    public static function incrementProjectIdentifierForUser(int $user_id): int
    {
        return (Project::where('user_id', '=', $user_id)->count()) + 1;
    }
}