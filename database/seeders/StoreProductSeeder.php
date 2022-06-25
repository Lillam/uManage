<?php

namespace Database\Seeders;

use App\Helpers\Text\TextHelper;
use App\Models\Store\StoreProduct;
use Illuminate\Database\Seeder;

class StoreProductSeeder extends Seeder
{
    public $key = 0;

    /**
    * Run the database seeders.
    *
    * @return void
    */
    public function run(): void
    {
        $products = [
            $this->increment() => (object) [
                'name' => 'Account Password Manager',
                'package' => [
                    \App\Http\Controllers\Web\Account\AccountController::class
                ],
                'price' => '9.99'
            ],
            $this->increment() => (object) [
                'name' => 'Daily Journals',
                'package' => [
                    \App\Http\Controllers\Web\Journal\JournalController::class,
                    \App\Http\Controllers\Web\Journal\JournalAchievementController::class,
                    \App\Http\Controllers\Web\Journal\JournalDreamController::class,
                    \App\Http\Controllers\Web\Journal\JournalFinanceController::class
                ],
                'price' => '19.99'
            ],
            $this->increment() => (object) [
                'name' => 'Project Management',
                'package' => [
                    \App\Http\Controllers\Web\Project\ProjectController::class,
                    \App\Http\Controllers\Web\Project\ProjectSettingController::class,
                    \App\Http\Controllers\Web\Project\ProjectUserContributorController::class,
                    \App\Http\Controllers\Web\Project\Task\TaskChecklistController::class,
                    \App\Http\Controllers\Web\Project\Task\TaskChecklistItemController::class,
                    \App\Http\Controllers\Web\Project\Task\TaskCommentController::class,
                    \App\Http\Controllers\Web\Project\Task\TaskController::class,
                    \App\Http\Controllers\Web\Project\Task\TaskFileController::class,
                    \App\Http\Controllers\Web\Project\Task\TaskIssueTypeController::class,
                    \App\Http\Controllers\Web\Project\Task\TaskLogController::class,
                    \App\Http\Controllers\Web\Project\Task\TaskPriorityController::class,
                    \App\Http\Controllers\Web\Project\Task\TaskStatusController::class,
                    \App\Http\Controllers\Web\Project\Task\TaskWatcherUserController::class,
                ],
                'price' => '59.99'
            ],
            $this->increment() => (object) [
                'name' => 'Time logging',
                'package' => [
                    \App\Http\Controllers\Web\TimeLog\TimeLogController::class
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
