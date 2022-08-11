<?php

namespace App\Events\Task;

use App\Models\Task\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    public string $field;
    public string $value;
    private string $target_channel;

    /**
    * Create a new event instance.
    *
    * @param $message
    * @param Task $task
    * @param $field
    *
    * @return void
    */
    public function __construct($message, Task $task, $field)
    {
        $this->message        = $message;
        $this->field          = $field;
        $this->value          = $task->$field;
        $this->target_channel = "task_{$task->id}";
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new Channel($this->target_channel);
    }
}
