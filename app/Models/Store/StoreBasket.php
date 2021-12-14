<?php

namespace App\Models\Store;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreBasket extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'store_basket';

    /**
    * @var string[]
    */
    protected $fillable = [
        'user_id',
        'store_product_id'
    ];

    /**
    * @var string[]
    */
    protected $casts = [
        'user_id'          => 'int',
        'store_product_id' => 'int'
    ];

    public $timestamps = false;

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Setters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with setting information around the specific model in
    | question, in this case: Store Basket.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the Store Basket.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: the Store Basket.
    |
    */

    /**
    * Each item that resides in this table, will belong to a user, each basket item can be placed against a user in the
    * system. this means we will have direct access to which particular user has this item in their basket... we will
    * also have knowledge of how many users have a particular item in their basket... (this way we can report on the
    * frontend saying (x) people are looking at this product).
    *
    * @return BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
    * Each item in the baasket model, will of course, have a product against it. of course, no sense of having a basket
    * item if there is nothing in your basket? this will be an easy method for grabbing the products that are residing
    * in everyone's basket; getting the product specifically.
    *
    * @return BelongsTo
    */
    public function store_product(): BelongsTo
    {
        return $this->belongsTo(StoreProduct::class, 'store_product_id', 'id');
    }
}
