<?php

namespace App\Repositories\Project;

use App\Models\Project\ProjectSetting;

class ProjectSettingRepository
{
    /**
    * This method is for updating the project setting, and will increment and decrement the value from before, and the
    * new value that's being thrown up... which will be referenced in the $new_value, $old_value variables.
    *
    * @param ProjectSetting $projectSetting
    * @param int $n  = $new  - what the new is
    * @param int $o  = $old  - what the old was
    *
    * @return void
    */
    public static function updateProjectSettingStatusStatistics(ProjectSetting $projectSetting, int $n, int $o): void
    {
        if ($projectSetting->{ProjectSetting::$TASKS_IN[$o]} > 0)
            $projectSetting->decrement(ProjectSetting::$TASKS_IN[$o]);
        $projectSetting->increment(ProjectSetting::$TASKS_IN[$n]);
    }

    /**
    * This method is utilised for creating a new project setting, when a user creates a new project the project is
    * dependant on having a setting row for it as well...
    *
    * @param integer $projectId
    *
    * @return void
    */
    public static function createProjectSettingUponProjectCreation(int $projectId): void
    {
        ProjectSetting::query()
            ->create([
                'project_id'         => $projectId,
                'view_id'            => 1,
                'tasks_total'        => 0,
                'tasks_in_todo'      => 0,
                'tasks_in_progress'  => 0,
                'tasks_in_completed' => 0,
                'tasks_in_archived'  => 0
            ]);
    }
}