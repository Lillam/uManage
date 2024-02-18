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
    * @param Collection $taskChecklists
    * @return Collection
    */
    public static function sortTaskChecklistProgress(Collection $taskChecklists): Collection
    {
        foreach ($taskChecklists as $taskChecklist) {
            $taskChecklist->total_checklist_items = 0;
            $taskChecklist->total_completed_checklist_items = 0;

            foreach ($taskChecklist->taskChecklistItems as $taskChecklistItem) {
                $taskChecklist->total_checklist_items += 1;

                if ($taskChecklistItem->is_checked === true) {
                    $taskChecklist->total_completed_checklist_items += 1;
                }
            }
        }

        return $taskChecklists;
    }
}
