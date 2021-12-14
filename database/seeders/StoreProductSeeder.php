<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Helpers\Text\TextHelper;
use App\Models\Store\StoreProduct;

class StoreProductSeeder extends Seeder
{
    public $key = 0;

    /**
    * Run the database seeders.
    *
    * @return void
    */
    public function run()
    {
        $products = [
            $this->increment() => (object) [
                'name' => 'Account Password Manager',
                'package' => [
                    \App\Http\Controllers\Account\AccountController::class
                ],
                'price' => '9.99'
            ],
            $this->increment() => (object) [
                'name' => 'Daily Journals',
                'package' => [
                    \App\Http\Controllers\Journal\JournalController::class,
                    \App\Http\Controllers\Journal\JournalAchievementController::class,
                    \App\Http\Controllers\Journal\JournalDreamController::class,
                ],
                'price' => '19.99'
            ],
            $this->increment() => (object) [
                'name' => 'Project Management',
                'package' => [
                    \App\Http\Controllers\Project\ProjectController::class,
                    \App\Http\Controllers\Project\ProjectSettingController::class,
                    \App\Http\Controllers\Project\ProjectUserContributorController::class,
                    \App\Http\Controllers\Task\TaskChecklistController::class,
                    \App\Http\Controllers\Task\TaskChecklistItemController::class,
                    \App\Http\Controllers\Task\TaskCommentController::class,
                    \App\Http\Controllers\Task\TaskController::class,
                    \App\Http\Controllers\Task\TaskFileController::class,
                    \App\Http\Controllers\Task\TaskIssueTypeController::class,
                    \App\Http\Controllers\Task\TaskLogController::class,
                    \App\Http\Controllers\Task\TaskPriorityController::class,
                    \App\Http\Controllers\Task\TaskStatusController::class,
                    \App\Http\Controllers\Task\TaskWatcherUserController::class,
                ],
                'price' => '59.99'
            ],
            $this->increment() => (object) [
                'name' => 'Time logging',
                'package' => [
                    \App\Http\Controllers\Timelog\TimelogController::class
                ],
                'price' => '5.99'
            ]
        ];

        foreach ($products as $product_id => $product) {
            StoreProduct::updateOrCreate(['id' => $product_id], [
                'id' => $product_id,
                'name' => $product->name,
                'alias' => TextHelper::slugify($product->name),
                'package' => json_encode($product->package),
                'price' => $product->price
            ]);
        }
    }

    /**
    * @return int
    */
    public function increment(): int
    {
        return $this->key += 1;
    }
}
