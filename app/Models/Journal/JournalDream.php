<?php

namespace App\Models\Journal;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Getters\GetterMarkdown;
use App\Models\Traits\Setters\SetterMarkdown;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalDream extends Model
{
    use SetterMarkdown, GetterMarkdown;

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'journal_dream';

    /**
    * @var array
    */
    protected $fillable = [
        'user_id',
        'rating',
        'content',
        'meaning',
        'when'
    ];

    /**
    * @var array
    */
    protected $casts = [
        'user_id'    => 'integer',
        'rating'     => 'integer',
        'content'    => 'string',
        'meaning'    => 'string',
        'when'       => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
    * @var bool
    */
    public $timestamps = true;

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Setters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting information around the specific model in
    | question.
    |
    */

    /**
    * @param $value
    * @return void
    */
    public function setContentAttribute($value): void
    {
        $this->attributes['content'] = $this->setParsedContent($value);
    }

    /**
    * @param $value
    * @return void
    */
    public function setMeaningAttribute($value): void
    {
        $this->attributes['meaning'] = $this->setParsedContent($value);
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
    * @param $value
    * @return string
    */
    public function getContentAttribute($value): string
    {
        return $this->getParsedContent($value);
    }

    /**
    * This here allows me to acquire a short version of the content, if one exists it's going to give me a stripped
    * tagged version of the attribute at hand "content" and limit it to 85 characters as a form of a short read before
    * clicking into the journal dream.
    *
    * @return string
    */
    public function getShortContent(): string
    {
        return $this->content !== null
            ? strip_tags(mb_substr($this->content, 0, 85)) . '...'
            : '';
    }

    /**
     * @param $value
     * @return string
     */
    public function getMeaningAttribute($value): string
    {
        return $this->getParsedContent($value);
    }

    /**
    * This method is strictly for returning a specific colour set when the user has opted to select a rating type, the
    * rating types from 5 - 1 are colour coded from purple, blue, green, orange, red. Purple being the best, red being
    * the worse.
    *
    * @return string
    */
    public function ratingColor(): string
    {
        switch ($this->rating) {
            case 1:  return '#f0506e';  // A shade of red     | Worse rating
            case 2:  return '#ffa500';  // A shade of orange  | Bad Rating
            case 3:  return '#32d296';  // A shade of green   | Ok Rating
            case 4:  return '#1e87f0';  // A shade of blue    | Good Rating
            case 5:  return '#0037ff';  // A shade of purple  | Best Rating
            default: return '#b1b1b1';  // A shade of grey    | Default
        }
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: the Journal Dream.
    |
    */

    /**
    * Each journal dream will be assigned to a singular user, this method will be minimally used, quite possibly only in
    * reporting purposes. this is an efficient and effective method for the retrieval of the user that is assigned to
    * a specific journal dream entry.
    *
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
