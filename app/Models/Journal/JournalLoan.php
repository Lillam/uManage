<?php

namespace App\Models\Journal;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalLoan extends Model
{
    /**
     * Pre-defining this model table name, this will be taking the name of the widget that it is, singular-ifying it
     * and storing it into it's parent property "$table"
     *
     * @var string
     */
    protected $table = 'journal_loan';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'amount',
        'reference',
        'interest',
        'when_loaned',
        'when_pay_back',
        'when_paid_back',
        'paid_back'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id'        => 'integer',
        'amount'         => 'float',
        'reference'      => 'string',
        'interest'       => 'float',
        'when_loaned'    => 'datetime',
        'when_pay_back'  => 'datetime',
        'when_paid_back' => 'datetime',
        'paid_back'      => 'boolean'
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

    /**
     * Each Journal loan in the system has the potential of having many items in the database (journal loan paybacks)
     * that are assigned to a single row here; we can acquire all the necessary items eagerly via this relationship
     * and return them with the overall loan.
     *
     * @return HasMany
     */
    public function paybacks(): HasMany
    {
        return $this->hasMany(JournalLoanPayback::class, 'journal_loan_id', 'id');
    }
}