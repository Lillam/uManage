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
            \App\Http\Controllers\Web\Account\AccountController::class
        ],
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
            \App\Http\Controllers\Web\Journal\JournalDashboardController::class,
            \App\Http\Controllers\Web\Journal\JournalController::class,
            \App\Http\Controllers\Web\Journal\JournalDreamDashboardController::class,
            \App\Http\Controllers\Web\Journal\JournalDreamController::class,
            \App\Http\Controllers\Web\Journal\JournalAchievementController::class,
            \App\Http\Controllers\Web\Journal\JournalReportController::class,
            \App\Http\Controllers\Web\Journal\JournalFinanceDashboardController::class,
            \App\Http\Controllers\Web\Journal\JournalFinanceController::class
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
            \App\Http\Controllers\Web\Project\ProjectDashboardController::class,
            \App\Http\Controllers\Web\Project\ProjectController::class,
            \App\Http\Controllers\Web\Project\ProjectSettingController::class,
            \App\Http\Controllers\Web\Project\ProjectUserContributorController::class,
            \App\Http\Controllers\Web\Project\Task\TaskDashboardController::class,
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
            \App\Http\Controllers\Web\Project\Task\TaskReportController::class,
        ]
    ],

    /**
    |-------------------------------------------------------------------------------------------------------------------
    | TimeLogging Module
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The TimeLogging module; with the required controllers that the user will be allowed access to when having access
    | to the module in question.
    |
    */
    4 => (object) [
        'name' => 'Time Keeping',
        'controllers' => [
            \App\Http\Controllers\Web\TimeLog\TimeLogController::class,
            \App\Http\Controllers\Web\TimeLog\TimeLogReportController::class,
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
            \App\Http\Controllers\Web\User\UserController::class,
            \App\Http\Controllers\Web\User\UserDetailController::class,
            \App\Http\Controllers\Web\User\UserSettingController::class,
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
            \App\Http\Controllers\Web\Store\StoreProductController::class,
            \App\Http\Controllers\Web\Store\StoreBasketController::class,
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
            \App\Http\Controllers\Web\System\SystemChangelogController::class,
            \App\Http\Controllers\Web\System\SystemController::class,
            \App\Http\Controllers\Web\System\SystemModuleAccessController::class,
            \App\Http\Controllers\Web\System\SystemModuleController::class,
        ]
    ]
];