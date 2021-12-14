<?php

namespace App\Repositories\Timelog;

use App\Models\Task\Task;

class TimelogRepository
{
    /**
    * Method for speifically iterating over the timelogs and assorting them into a readable array that I can return
    * into a JSON array from the timelog controller method that calls this. this will give the system a day by day
    * ordered timelog array along with the hours that sit against that particular day.
    *
    * @param $timelogs
    * @return object
    */
    public static function sortTimelogs($timelogs): object
    {
        $timelog_data = $timelog_date_hours = [];

        foreach ($timelogs as $timelog) {
            // organise these timelogs into an associate array, by date.
            $timelog_data[$timelog->from->format('d-m-Y')][] = (object) [
                'id'         => $timelog->id,
                'from'       => $timelog->from,
                'note'       => $timelog->getShortNote(),
                'time_spent' => self::convertTimelogTimeSpent($timelog->time_spent,  true),
                'task'       => $timelog->task,
                'project'    => $timelog->project
            ];

            if (! empty($timelog_date_hours[$timelog->from->format('d-m-Y')])) {
                $timelog_date_hours[$timelog->from->format('d-m-Y')] += $timelog->time_spent;
            } else {
                $timelog_date_hours[$timelog->from->format('d-m-Y')] = $timelog->time_spent;
            }
        }

        return (object) [
            "timelog_data"       => $timelog_data,
            "timelog_date_hours" => $timelog_date_hours
        ];
    }

    /**
    * This method is converting the timespent timelogging, timelogs will be stored in accurate minutes. so, we will be
    * first trying to calculate how many we have left after we have taken all the 60s (hours) away, and whatever is
    * left will be the direct minutes over hours
    *
    * @param $time_spent
    * @return string
    */
    public static function convertTimelogTimeSpent($time_spent, $short = false): string
    {
        $minutes = ($time_spent % 60);
        $hours = round(($time_spent - $minutes) / 60, 0);

        $return  = (int) $hours   !== 0 ? "{$hours}h" : '';
        $return .= (int) $minutes !== 0 ? " {$minutes}m" : '';

        return $return;
    }

    /**
    * This method translates the timespent from a string, 1h 30m into a minute number, 1h 30m will translate directly to
    * 90 into the database. we are storing all values as minutes in the database.
    *
    * @param $time_spent
    * @return int
    */
    public static function translateTimespent($time_spent): int
    {
        $time_pieces = explode(' ', $time_spent);
        $time_to_return = 0;
        foreach ($time_pieces as $time_piece) {
            if (mb_strpos($time_piece, 'h') !== false) {
                $time_piece = str_replace('h', '', $time_piece);
                $time_to_return += ($time_piece * 60);
            }

            if (mb_strpos($time_piece, 'm') !== false) {
                $time_piece = str_replace('m', '', $time_piece);
                $time_to_return += $time_piece;
            }
        }

        return $time_to_return;
    }

    /**
    * This method is entirely for mapping out the task timelogs to a user specific.  this will be mapping only the
    * necessary information, the user, and the time spent.
    *
    * @param Task $task
    * @return array|\Illuminate\Support\Collection
    */
    public static function sortTaskTimelogs(Task $task)
    {
        $timelog_map = collect();

        if (! $task instanceof Task) {
            return $timelog_map;
        }

        foreach ($task->task_timelogs as $timelog) {
            if (! empty($timelog_map[$timelog->user_id])) {
                $timelog_map[$timelog->user_id]->time_spent += $timelog->time_spent;
                continue;
            }

            // we should only hit this section of the code, if we have not made it into the if statement above. this
            // code will be ignored (this is setting the item into the array.
            $timelog_map[$timelog->user_id] = (object) [
                'user' => $timelog->user,
                'time_spent' => $timelog->time_spent
            ];
        }

        return collect($timelog_map);
    }

    /**
    * This function is entirely for grabbing the total amount of time logged against a task, so we are able to calculate
    * a percentage of which a user has contributed to the task in question, this will be calculating EVERYONE's time
    * against a task.
    *
    * @param Task $task
    * @return int
    */
    public static function getTotalTimeLogged(Task $task): int
    {
        $total_time_logged = 0;
        $task->task_timelogs->map(function ($timelog) use (&$total_time_logged) {
            $total_time_logged += $timelog->time_spent;
        });

        return $total_time_logged;
    }
}