<?php

namespace App\Console\Commands\System;

use Illuminate\Console\Command;
use App\Jobs\LocalStore\Task\TaskLocalStoreJob;
use App\Jobs\LocalStore\Account\AccountLocalStoreJob;
use App\Jobs\LocalStore\Journal\JournalLocalStoreJob;
use App\Jobs\LocalStore\Project\ProjectLocalStoreJob;
use App\Jobs\LocalStore\Journal\JournalDreamLocalStoreJob;

class ExtractDatabaseCommand extends Command
{
    /**
     * @var string
     */
    protected $name = "system.database.extract";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "system:database:extract";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Extract the database";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handle the command of extracting the database.
     *
     * This command handler is simply going to iterate over all the local store jobs that we've got set up in the system
     * and pull them out into their own files that has been specified below.
     *
     * @return int
     */
    public function handle(): int
    {
        foreach (
            [
                AccountLocalStoreJob::class => "external/accounts.json",
                JournalDreamLocalStoreJob::class => "external/dream_journals.json",
                JournalLocalStoreJob::class => "external/journals.json",
                ProjectLocalStoreJob::class => "external/projects.json",
                TaskLocalStoreJob::class => "external/tasks.json",
            ]
            as $job => $destination
        ) {
            // log and dispatch the job in question. This will simply perform the handle method of all the above...
            // which in turn dispatches a local store of all the data that resides in the database into a new location
            // under /external.
            $this->info("Dispatching $job...");
            dispatch(new $job($destination));
            $this->info("Dispatched $job...");
        }

        return 0;
    }
}
