<?php

/**
|-----------------------------------------------------------------------------------------------------------------------
| Application Modules
|-----------------------------------------------------------------------------------------------------------------------
|
| This file will be where all the applications modules will be defined by design... this will reference the types of
| modules that will be accessible... any placed in here, will also be placed into the database as well. This will be an
| array of Controllers that the system will be able to reference, rather than utilising a row in the database against
| the user.
|
*/

return [
    // Account Management
    /**
    |-------------------------------------------------------------------------------------------------------------------
    | Account Management Module
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The account management module; with the required controllers that the user will be allowed access to.
    |
    */
    1 => (object) [
        'name' => 'Account Management',
        'controllers' => [
            \App\Http\Controllers\Account\AccountController::class
        ]
    ],

    /**
    |-------------------------------------------------------------------------------------------------------------------
    | Journals Module
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The journals module; with the required controllers that the user will be allowed access to when having access to
    | the module in question.
    |
    */
    2 => (object) [
        'name' => 'Journals',
        'controllers' => [
            \App\Http\Controllers\Journal\JournalDashboardController::class,
            \App\Http\Controllers\Journal\JournalController::class,
            \App\Http\Controllers\Journal\JournalDreamDashboardController::class,
            \App\Http\Controllers\Journal\JournalDreamController::class,
            \App\Http\Controllers\Journal\JournalAchievementController::class,
            \App\Http\Controllers\Journal\JournalReportController::class,
            \App\Http\Controllers\Journal\JournalFinanceDashboardController::class,
            \App\Http\Controllers\Journal\JournalFinanceController::class
        ]
    ],

    /**
    |-------------------------------------------------------------------------------------------------------------------
    | Project Management Module
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The project management module; with the required controllers that the user will be allowed access to when having
    | access to the module in question.
    |
    */
    3 => (object) [
        'name' => 'Project Management',
        'controllers' => [
            \App\Http\Controllers\Project\ProjectDashboardController::class,
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
            \App\Http\Controllers\Task\TaskReportController::class,
        ]
    ],

    /**
    |-------------------------------------------------------------------------------------------------------------------
    | Timelogging Module
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The Timelogging module; with the required controllers that the user will be allowed access to when having access
    | to the module in question.
    |
    */
    4 => (object) [
        'name' => 'Time Keeping',
        'controllers' => [
            \App\Http\Controllers\Timelog\TimelogController::class,
            \App\Http\Controllers\Timelog\TimelogReportController::class,
        ]
    ],

    /**
    |-------------------------------------------------------------------------------------------------------------------
    | User Module
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The user module; with the required controllers that the user will be allowed access to when having access to the
    | module in question.
    |
    */
    5 => (object) [
        'name' => 'Users',
        'controllers' => [
            \App\Http\Controllers\User\UserController::class,
            \App\Http\Controllers\User\UserDetailController::class,
            \App\Http\Controllers\User\UserSettingController::class,
        ]
    ],

    /**
    |-------------------------------------------------------------------------------------------------------------------
    | Store Module
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The store module; with the required controllers that the user will be allowed access to when having access to the
    | module in question.
    |
    */
    6 => (object) [
        'name' => 'Store',
        'controllers' => [
            \App\Http\Controllers\Store\StoreProductController::class,
            \App\Http\Controllers\Store\StoreBasketController::class,
        ]
    ],

    /**
    |-------------------------------------------------------------------------------------------------------------------
    | System Module
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The system module; with the required controllers that the user will be allowed access to when having access to the
    | module in question.
    |
    */
    7 => (object) [
        'name' => 'System',
        'controllers' => [
            \App\Http\Controllers\System\SystemChangelogController::class,
            \App\Http\Controllers\System\SystemController::class,
            \App\Http\Controllers\System\SystemModuleAccessController::class,
            \App\Http\Controllers\System\SystemModuleController::class,
        ]
    ]
];