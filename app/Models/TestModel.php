<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasFactory;

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | TestModel Information
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next title is regarding the base information for the model itself, such as
    | database name, fields and more.
    |
    */

    /**
    * Pre-defining the table for TestModel
    *
    * @var string
    */
    protected string $table = '';

    /**
    * Pre-defining the primary key column for TestModel
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
    * Pre-defining the columns that are fillable for TestModel
    *
    * @var string[]
    */
    protected array $fillable = [];

    /**
    * Pre-defining the columns that want to be cast to a specific value on return for TestModel
    *
    * @param string[]
    */
    protected array $casts = [];

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | TestModel Setters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting information around the specific model in
    | question.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | TestModel Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | TestModel Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has.
    |
    */
}
