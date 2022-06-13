<?php

namespace App\Repositories\TimeLog;

use App\Models\Task\Task;
use Illuminate\Support\Collection;

class TimeLogRepository
{
    /**
    * Method for specifically iterating over the time logs and assorting them into a readable array that I can return
    * into a JSON array from the time_log controller method that calls this. this will give the system a day by day
    * ordered time_log array along with the hours that sit against that particular day.
    *
    * @param $time_logs
    * @return object
    */
    public static function sortTimeLogs($time_logs): object
    {
        $time_log_data = $time_log_date_hours = [];

        foreach ($time_logs as $time_log) {
            // organise these time logs into an associate array, by date.
            $time_log_data[$time_log->from->format('d-m-Y')][] = (object) [
                'id'         => $time_log->id,
                'from'       => $time_log->from,
                'note'       => $time_log->getShortNote(),
                'time_spent' => self::convertTimeLogTimeSpent($time_log->time_spent),
                'task'       => $time_log->task,
                'project'    => $time_log->project
            ];

            if (! empty($time_log_date_hours[$time_log->from->format('d-m-Y')])) {
                $time_log_date_hours[$time_log->from->format('d-m-Y')] += $time_log->time_spent;
                continue;
            }

            $time_log_date_hours[$time_log->from->format('d-m-Y')] = $time_log->time_spent;
        }

        return (object) [
            "time_log_data"       => $time_log_data,
            "time_log_date_hours" => $time_log_date_hours
        ];
    }

    /**
    * This method is converting the time spent time logging, time logs will be stored in accurate minutes. so, we will
    * be first trying to calculate how many we have left after we have taken all the 60s (hours) away, and whatever is
    * left will be the direct minutes over hours
    *
    * @param $time_spent
    * @return string
    */
    public static function convertTimeLogTimeSpent($time_spent): string
    {
        $minutes = ($time_spent % 60);
        $hours = round(($time_spent - $minutes) / 60);

        $return  = (int) $hours   !== 0 ? "{$hours}h" : '';
        $return .= $minutes !== 0 ? " {$minutes}m" : '';

        return $return;
    }

    /**
    * This method translates the time spent from a string, 1h 30m into a minute number, 1h 30m will translate directly
    * to 90 into the database. we are storing all values as minutes in the database.
    *
    * @param $time_spent
    * @return int
    */
    public static function translateTimeSpent($time_spent): int
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
    * This method is entirely for mapping out the task time logs to a user specific.  this will be mapping only the
    * necessary information, the user, and the time spent.
    *
    * @param Task $task
    * @return array|Collection
    */
    public static function sortTaskTimeLogs(Task $task): array|Collection
    {
        $time_log_map = collect();

        foreach ($task->task_time_logs as $time_log) {
            if (! empty($time_log_map[$time_log->user_id])) {
                $time_log_map[$time_log->user_id]->time_spent += $time_log->time_spent;
                continue;
            }

            // we should only hit this section of the code, if we have not made it into the if statement above. this
            // code will be ignored (this is setting the item into the array).
            $time_log_map[$time_log->user_id] = (object) [
                'user' => $time_log->user,
                'time_spent' => $time_log->time_spent
            ];
        }

        return collect($time_log_map);
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
        $task->task_time_logs->map(function ($time_log) use (&$total_time_logged) {
            $total_time_logged += $time_log->time_spent;
        });

        return $total_time_logged;
    }
}