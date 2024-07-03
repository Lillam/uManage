<?php

namespace App\Models\Journal;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalDiet extends Model
{
    protected $table = 'journal_diet';

    protected $fillable = [
        'user_id',
        'when'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'when'    => 'datetime',
    ];

    /*
    | ------------------------------------------------------------------------------------------------------------------
    | Relationships
    | ------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
     * Each journal will be assigned to a singular user, this method will be minimally used, quite possibly only in
     * reporting purposes... this is an efficient and effective method for the retrieval of the user that is assigned to
     * a specific journal entry.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    /**
     * Each journal will have multiple items, this method will be used to retrieve all items that are assigned to a
     * specific journal entry.
     *
     * @return HasMany
     */
    public function dietItems(): HasMany
    {
        return $this->hasMany(JournalDietItems::class, 'diet_journal_id', 'id');
    }
}
