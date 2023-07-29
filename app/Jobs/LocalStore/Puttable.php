<?php

namespace App\Jobs\LocalStore;

trait Puttable
{
    /**
     * Data of which is going to be put here; this will be in the format of an array.
     *
     * @var array
     */
    protected array $put;

    /**
     * This method simply resets the put array back to factory setting of being an empty array; ready to be meddled
     * with once again.
     *
     * @return void
     */
    public function reset(): void
    {
        $this->put = [];
    }
}