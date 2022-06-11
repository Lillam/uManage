<?php

namespace App\Models\Journal;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Getters\GetterMarkdown;
use App\Models\Traits\Setters\SetterMarkdown;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Journal extends Model
{
    use SetterMarkdown, GetterMarkdown;

    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'journal';

    /**
    * @var string[]
    */
    protected $fillable = [
        'user_id',
        'rating',
        'overall',
        'lowest_point',
        'highest_point',
        'when'
    ];

    /**
    * @var string[]
    */
    protected $casts = [
        'user_id'       => 'int',
        'rating'        => 'int',
        'overall'       => 'string',
        'lowest_point'  => 'string',
        'highest_point' => 'string',
        'when'          => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime'
    ];

    /*
    | ------------------------------------------------------------------------------------------------------------------
    | Setters
    | ------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting information around the specific model in
    | question, in this case: the Journal.
    |
    */

    /**
    * @param $value
    * @return void
    */
    public function setOverallAttribute($value): void
    {
        $this->attributes['overall'] = $this->setParsedContent($value);
    }

    /**
    * @param $value
    * @return void
    */
    public function setLowestPointAttribute($value): void
    {
        $this->attributes['lowest_point'] = $this->setParsedContent($value);
    }

    /**
    * @param $value
    * @return void
    */
    public function setHighestPointAttribute($value): void
    {
        $this->attributes['highest_point'] = $this->setParsedContent($value);
    }

    /*
    | ------------------------------------------------------------------------------------------------------------------
    | Getters
    | ------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the Journal
    |
    */

    /**
    * by default; the overall attribute is considered a 'markdown field' which means that all html will be converted
    * into markdown before being inserted into the database, when we retrieve the data back, we are going to want to
    * parse the markdown back into html so that html web pages can render it.
    *
    * @param $value
    * @return string
    */
    public function getOverallAttribute($value): string
    {
        return $this->getParsedContent($value);
    }

    /**
    * @param int $amount
    * @return string
    */
    public function getShortOverall(int $amount = 85): string
    {
        return $this->overall !== null
            ? strip_tags(mb_substr($this->overall, 0, $amount)) . '...'
            : '';
    }

    /**
    * by default; the lowest point attribute is considered a 'markdown field' which means that all html will be converted
    * into markdown before being inserted into the database, when we retrieve the data back, we are going to want to
    * parse the markdown back into html so that html web pages can render it.
    *
    * @param $value
    * @return string
    */
    public function getLowestPointAttribute($value): string
    {
        return $this->getParsedContent($value);
    }

    /**
    * by default; the highest point attribute is considered a 'markdown field' which means that all html will be converted
    * into markdown before being inserted into the database, when we retrieve the data back, we are going to want to
    * parse the markdown back into html so that html web pages can render it.
    *
    * @param $value
    * @return string
    */
    public function getHighestPointAttribute($value): string
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
    | ------------------------------------------------------------------------------------------------------------------
    | Relationships
    | ------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: the Journal.
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
    * Each journal will have a variety of achievements assigned to it which will obviously be hooked up to the
    * JournalAchievement model. This will return all achievements for this journal on a many to one type relationship
    *
    * @return HasMany
    */
    public function achievements(): HasMany
    {
        return $this->hasMany(JournalAchievement::class, 'journal_id', 'id');
    }
}
