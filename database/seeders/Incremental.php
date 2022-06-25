<?php

namespace Database\Seeders;

trait Incremental
{
    protected int $key = 0;

    /**
     * @return int
     */
    public function increment(): int
    {
        $this->key += 1;

        return $this->key;
    }
}