<?php

namespace App\Http\Controllers\Web\System;

use App\Http\Controllers\Controller;
use App\Jobs\Account\AccountLocalStoreJob;
use App\Jobs\Journal\JournalDreamLocalStoreJob;
use App\Jobs\Journal\JournalLocalStoreJob;
use App\Jobs\Project\ProjectLocalStoreJob;
use App\Jobs\Task\TaskLocalStoreJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{
    public function _performRandomJob()
    {
//        $dates = (new DateRule())
//            ->setStartDate('2021-01-28 00:00')
//            ->setCount(5)
//            ->setFrequency('yearly')
//            ->create();
//
//        dd($dates);

        $quotes = explode("\n", Storage::disk('local')->get('twitch_quotes/quotes.txt'));
//
//        foreach ($quotes as $key => $quote) {
//            $key ++;
//            echo str_replace('StreamElements: @Lillam, ', '', $quote);
//            echo '<Br />';
//            if ($key % 5 === 0) {
//                echo '<br />';
//            }
//        }
//
//        dd('here');

        return view('system.test', compact('quotes'));
    }

    /**
    * This method is just a hub for being able to acquire all the emojis that are supported by the system, I will be
    * collectively going through all the emojis in place and adding their counterpart into the system, into the php
    * array from the file "assets/vendor/emoji/emoji.php"... this will give us knowledge of every emoji that is in the
    * system as well as being able to collectively store these somewhere...
    *
    * @param Request $request
    * @return JsonResponse
    */
    public function _getSummernoteEmojis(Request $request): JsonResponse
    {
        return response()->json(include 'assets/vendor/emoji/emoji.php');
    }

    /**
    * This method dispatches a localised storage system... this will keep everything on the local machine, this method
    * is essentially a placeholder for storing data elsewhere off of the machine that is being maintained... aka...
    * if there are some database maintenance or new features, or even moving data off of the server, these jobs are
    * designed to locally store them data from the database that won't be initially seeded; to retain a checkpoint
    * on where the application is.
    *
    * @return RedirectResponse
    */
    public function _storeAllModulesLocally(): RedirectResponse
    {
        $local_store_jobs = [
            TaskLocalStoreJob::class,
            ProjectLocalStoreJob::class,
            AccountLocalStoreJob::class,
            JournalLocalStoreJob::class,
            JournalDreamLocalStoreJob::class
        ];

        foreach ($local_store_jobs as $local_store_job) {
            dispatch(new $local_store_job);
        }

        return back();
    }
}