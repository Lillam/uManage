<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait CompositeKey
{
    /**
    * Here we are going to be overriding everything to do with the model itself; and this is just going to be checking
    * whether or not the model at hand has a composite key, and if not, just return the normal happening of being
    * able to save a query, otherwise, find all the keys that are assigned to the model at hand, and then building
    * a saving query regarding all the keys that have been applied.
    *
    * @param Builder $query
    * @return Builder
    */
    protected function setKeysForSaveQuery($query): Builder
    {
        // if the key type of a string, then we are just going to return the normal method of the save parameters.
        if (gettype($keys = $this->getKeyName()) === 'string')
            return $query->where($keys, '=', $this->getAttribute($this->getKeyName()));

        // if the key type has been designated an array; then we are going to be walking over the keys that are assigned
        // against the model in question, and then apply that to what is going to be attempted to eb saved against; this
        // method should allow for a composite key of more than 1 keys, and going up to an endless amount of keys...
        array_walk($keys, function ($column) use (&$query) {
            $query->where($column, '=', $this->getAttribute($column));
        }); return $query;
    }

//    /**
//    * Define a one-to-many relationship.
//    *
//    * @param  string  $related
//    * @param  string|null|array  $foreign_keys
//    * @param  string|null|array  $local_keys
//    * @return \Illuminate\Database\Eloquent\Relations\HasMany
//    */
//    public function hasMany($related, $foreign_keys = null, $local_keys = null)
//    {
//        $instance = $this->newRelatedInstance($related);
//
//        // lets check to see whether or not the related model is utilising the composite key attribute, and if that's
//        // the case, then we can safely say, it's ok to return the initial default method that has been defined in the
//        // laravel package.
//        if (! $instance->uses_composite_key)
//            return $this->defaultHasMany($related, "{$instance->getTable()}.$foreign_keys", $local_keys);
//
//        $instance_query = $instance->newQuery();
//        $foreign_keys   = $foreign_keys ?: $this->getForeignKey();
//        $local_keys     = $local_keys ?: $this->getKeyName();
//
//        if (is_array($local_keys)) {
//            foreach ($local_keys as $key => $local_key) {
//                $instance_query->where("{$instance->getTable()}.{$foreign_keys[$key]}", '=', 1);
//            }
//        }
//
//        return $this->newHasMany($instance_query, $this, "{$instance->getTable()}.$foreign_keys", $local_keys);
//    }
//
//    /**
//    * @param $related
//    * @param null $foreignKey
//    * @param null $localKey
//    * @return \Illuminate\Database\Eloquent\Relations\HasMany
//    */
//    public function defaultHasMany($related, $foreignKey = null, $localKey = null)
//    {
//        $instance   = $this->newRelatedInstance($related);
//        $foreignKey = $foreignKey ?: $this->getForeignKey();
//        $localKey   = $localKey ?: $this->getKeyName();
//        return $this->newHasMany($instance->newQuery(), $this, "{$instance->getTable()}.$foreignKey", $localKey);
//    }
}