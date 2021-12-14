<?php

namespace App\Models\Project;

use App\Models\Traits\CompositeKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectUserContributor extends Model
{
    use CompositeKey;

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'project_user_contributor';

    /**
    * @var array
    */
    protected $fillable = [
        'project_id',
        'user_id',
    ];

    /**
    * @var bool
    */
    public $incrementing = false;

    /**
    * @var array
    */
    protected $casts = [
        'project_id' => 'int',
        'user_id'    => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the Project User Contributor.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: the Project User Contributor.
    |
    */

    /**
    * This method is for being able to grab the user whilst looking at project user contributors. this is entirely for
    * convenience more than anything, if we are looking at the contributors of projects then it's nice to be able to
    * grab the user in question.
    *
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'user_id', 'id');
    }

    /**
    * This method is for being able to grab the project whilst looking at project user contributors. this is entirely
    * for convenience more than anything, if we are looking at the contributors of a project, or looking at the
    * relationship as a whole, then it's nice to grab the project in question with ease.
    *
    * @return BelongsTo
    */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
