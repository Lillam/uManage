<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreProduct extends Model
{
    /**
    * Pre-defining this models table name, this will be taking the name of the widget that it is, singular-ifying it
    * and storing it into it's parent protected property "table"
    *
    * @var string
    */
    protected $table = 'store_product';

    /**
    * @var array
    */
    protected $fillable = [
        'name',
        'alias',
        'package',
        'price'
    ];

    /**
    * @var array
    */
    protected $casts = [
        'name'    => 'string',
        'alias'   => 'string',
        'package' => 'json',
        'price'   => 'float'
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
    | question, in this case: the Store Product.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Getters
    |-------------------------------------------------------------------------------------------------------------------
    | Logic from this point until the next titling is 100% to do with getting information around the specific model in
    | question, in this case: the Store Product.
    |
    */

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Relationships
    |-------------------------------------------------------------------------------------------------------------------
    | The information from this point on will 100% be around the relationships that this specific model has. In this
    | specific instance: the Store Product.
    |
    */

    /**
    * @return HasMany
    */
    public function store_basket_products(): HasMany
    {
        return $this->hasMany(StoreBasket::class, 'store_product_id', 'id');
    }
}
