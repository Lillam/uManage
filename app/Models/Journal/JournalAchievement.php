<?php

namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalAchievement extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'journal_achievement';

    /**
    * @var array
    */
    protected $fillable = [
        'journal_id',
        'name'
    ];

    /**
    * @var array
    */
    protected $casts = [
        'id'         => 'int',
        'journal_id' => 'int',
        'name'       => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
    * @var bool
    */
    public $timestamps = true;

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the Journal Achievement
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: the Journal Achievement.
    |
    */

    /**
    * Each JournalAchievement that gets added into the system, will be attached to a journal. Because the
    * JournalAchievement in question will have a journal_id associated to it, we have the ability to collect the
    * journal from the database. This will offer the ability to be able to view all of the journal achievements and
    * highlight where the achievement is coming from and what day it is associated to.
    *
    * @return BelongsTo
    */
    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class, 'id', 'journal_id');
    }
}
