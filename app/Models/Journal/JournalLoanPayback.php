<?php

namespace App\Models\Journal;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalLoanPayback extends Model
{
    protected $table = 'journal_loan_payback';

    public $timestamps = false;

    protected $fillable = [
        'journal_loan_id',
        'user_id',
        'amount',
        'paid_when'
    ];

    protected $casts = [
        'amount'      => 'float',
        'paid_when'   => 'datetime'
    ];

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Setters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting information around the specific model in
    | question
    |
    */

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
     * Each journal pay back will be assigned to a singular user, this method will be minimally used, quite possibly
     * only in reporting purposes. this is an efficient and effective method for the retrieval of the user that is
     * assigned to a specific journal loan payback entry.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    /**
     * Each journal payback entry is assigned to a journal loan, each entry will be able to be returned with
     * their parent respective loan.
     *
     * @return BelongsTo
     */
    public function journalLoan(): BelongsTo
    {
        return $this->belongsTo(JournalLoan::class, 'id', 'journal_loan_id');
    }
}