<?php

namespace App\Jobs\LocalStore;

trait Destinationable
{
    /**
     * The destination in which the local store job is going to find the directory going.
     *
     * @var string
     */
    protected string $destination;

    /**
     * Set the destination of the local store
     *
     * @param string|null $destination
     * @return void
     */
    public function setDestination(?string $destination = null): void
    {
        if ($destination) {
            $this->destination = $destination;
        }
    }

    /**
     * Get the destination in which the job will be situated.
     *
     * @param string|null $fileName
     * @return string
     */
    public function getDestination(?string $fileName = null): string
    {
        if ($fileName) {
            return $this->destination . "/$fileName";
        }

        return $this->destination;
    }
}