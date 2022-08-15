<?php

namespace App\Models\Journal;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Getters\GetterMarkdown;
use App\Models\Traits\Setters\SetterMarkdown;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalFood extends Model
{
    use SetterMarkdown, GetterMarkdown;

    /**
     * Pre-defining this model table name, this will be taking the name of the widget that it is, singular-ifying it
     * and storing it into it's parent property "$table"
     *
     * @var string
     */
    protected $table = 'journal_food';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'overall',
        'when',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id'    => 'integer',
        'overall'    => 'string',
        'when'       => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Setters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting information around the specific model in
    | question
    |
    */

    /**
     * @param string $value
     * @return void
     */
    public function setOverallAttribute(string $value): void
    {
        $this->attributes['overall'] = $this->setParsedContent($value);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question.
    |
    */

    /**
     * @param string $value
     * @return string
     */
    public function getOverallAttribute(string $value): string
    {
        return $this->getParsedContent($value);
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * Each journal food will be assigned to a singular user, this method will be minimally used, quite possibly only in
    * reporting purposes. this is an efficient and effective method for the retrieval of the user that is assigned to
    * a specific journal food entry.
    *
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}