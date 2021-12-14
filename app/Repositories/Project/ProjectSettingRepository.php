<?php

namespace App\Repositories\Project;

use App\Models\Project\ProjectSetting;

class ProjectSettingRepository
{
    /**
    * This method is for updating the project setting, and will increment and decrement the value from before, and the
    * new value that's being thrown up... which will be referenced in the $new_value, $old_value variables.
    *
    * @param ProjectSetting $project_setting
    * @param int $n  = $new  - what the new is
    * @param int $o  = $old  - what the old was
    *
    * @return void
    */
    public static function updateProjectSettingStatusStatistics(ProjectSetting $project_setting, int $n, int $o): void
    {
        if ($project_setting->{ProjectSetting::$tasks_in[$o]} > 0)
            $project_setting->decrement(ProjectSetting::$tasks_in[$o]);
        $project_setting->increment(ProjectSetting::$tasks_in[$n]);
    }

    /**
    * This method is utilised for creating a new project setting, when a user creates a new project the project is
    * dependant on having a setting row for it as well...
    *
    * @param integer $project_id
    *
    * @return void
    */
    public static function createProjectSettingUponProjectCreation(int $project_id): void
    {
        ProjectSetting::create([
            'project_id'         => $project_id,
            'view_id'            => 1,
            'tasks_total'        => 0,
            'tasks_in_todo'      => 0,
            'tasks_in_progress'  => 0,
            'tasks_in_completed' => 0,
            'tasks_in_archived'  => 0
        ]);
    }
}