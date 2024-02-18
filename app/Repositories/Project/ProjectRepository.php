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
    * @param int $userId
    * @return int
    */
    public static function incrementProjectIdentifierForUser(int $userId): int
    {
        return (
            Project::query()
                ->where('user_id', '=', $userId)
                ->count()
        ) + 1;
    }
}
