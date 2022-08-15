<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskFile extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'task_file';

    /**
    * Everything that is fillable (other than created_at, updated_at) wants to be inserted into here, otherwise the
    * system won't recognise the entries as a submittable value
    *
    * @var array
    */
    protected $fillable = [
        'task_id',
        'file',
        'mimetype'
    ];

    /**
    * These are the casts that the values in the database are going to come into the system as. All elements against
    * this model that is stored within the database are going to want to have their default defined cast.
    *
    * @var array
    */
    protected $casts = [
        'task_id'    => 'int',
        'file'       => 'string',
        'mimetype'   => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * This method is for convenience, if we are looking at all the files in the system via an interface, then we have
    * the option to be able to view the task in question that the file belongs to, This shouldn't be used too often
    * however is strictly here for convenience.
    *
    * @return BelongsTo
    */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
