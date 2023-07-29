<?php

namespace App\Jobs\LocalStore;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class LocalStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Destinationable, Puttable;

    /**
     * @param string $disk
     * @param string $file
     * @return $this
     */
    public function putToStorage(string $disk, string $file = ''): static
    {
        Storage::disk($disk)->put($this->getDestination($file), json_encode($this->put));

        return $this;
    }
}