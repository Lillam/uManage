<?php

namespace App\Models\Journal;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalFinance extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'journal_finance';

    /**
    * @var string[]
    */
    protected $fillable = [
        'user_id',
        'spend',
        'where',
        'when'
    ];

    /**
    * @var string[]
    */
    protected $casts = [
        'user_id' => 'integer',
        'spend'   => 'float',
        'when'    => 'datetime',
        'where'   => 'string'
    ];

    /*
    | ------------------------------------------------------------------------------------------------------------------
    | Setters
    | ------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting information around the specific model in
    | question.
    |
    */

    /*
    | ------------------------------------------------------------------------------------------------------------------
    | Getters
    | ------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question.
    |
    */

    /*
    | ------------------------------------------------------------------------------------------------------------------
    | Relationships
    | ------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * This method allows us to quickly acquire the users along with all the spending that are assigned to a user
    * in particular; allowing us to report and find out what user is the owner of the financial entity that had been
    * entered into the database.
    *
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}