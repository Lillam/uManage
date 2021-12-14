<?php

namespace App\Printers\Project;

use App\Models\Project\Project;

class ProjectPrinter
{
    /**
    * @param Project $project
    * @return string
    */
    public static function getBadge(Project $project): string
    {
        if ($project->code === null) {
            return '';
        }

        return "<span class='badge' style='background-color: {$project->getColor()}'>$project->code</span>";
    }
}