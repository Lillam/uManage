<?php

namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalDietItems extends Model
{
    protected $table = 'journal_diet_items';

    protected $fillable = [
        'journal_diet_id',
        'journal_diet_item_id'
    ];

    protected $casts = [
        'journal_diet_id'      => 'int',
        'journal_diet_item_id' => 'int'
    ];

    /*
    | ------------------------------------------------------------------------------------------------------------------
    | Relationships
    | ------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */

    /**
    * Each journal item will be assigned to a singular journal, this method will be minimally used, quite possibly only in
    * reporting purposes... this is an efficient and effective method for the retrieval of the journal that is assigned to
    * a specific journal item entry.
    *
    * @return BelongsTo
    */
    public function dietItem(): BelongsTo
    {
        return $this->belongsTo(JournalDietItem::class, 'id', 'diet_journal_item_id');
    }
}
