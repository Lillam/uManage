<?php

namespace App\Repositories\Task;

use Illuminate\Database\Eloquent\Collection;

class TaskChecklistRepository
{
    /**
    * This particular method is to iterate over all task checklist items inside the passed task checklists which will
    * then check the state of each checklist items... and create a percentage based on what is already there... this
    * will be latching two temporary values onto the collection of checklists however won't always be there, depends on
    * the use-case.
    *
    * @param Collection $task_checklists
    * @return Collection
    */
    public static function sortTaskChecklistProgress(Collection $task_checklists)
    {
        foreach ($task_checklists as $task_checklist) {
            $task_checklist->total_checklist_items = 0;
            $task_checklist->total_completed_checklist_items = 0;
            foreach ($task_checklist->task_checklist_items as $task_checklist_item) {
                $task_checklist->total_checklist_items += 1;
                if ($task_checklist_item->is_checked === true) {
                    $task_checklist->total_completed_checklist_items += 1;
                }
            }
        }

        return $task_checklists;
    }
}