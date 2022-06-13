<?php

return [
   /*
   |--------------------------------------------------------------------------------------------------------------------
   | System Module Languages
   |--------------------------------------------------------------------------------------------------------------------
   |
   | The following language lines are used during authentication for various messages that we need to display to the
   | user. You are free to modify these language lines according to your application's requirements.
   |
   */

    'model' => [
        'statuses' => [
            'enabled' => 'Enabled',
            'disabled' => 'Disabled',
            'maintenance' => 'Maintenance'
        ]
    ],
    'App\Http\Controllers\Account\AccountController'                => [
        'name' => 'Accounts',
        'description' => '
            Are you in need of a place to store all your accounts and passwords? This package will unlock the 
            ability for you to store your accounts in a dedicated protected database, completely encrypted; and 
            only you, will be able to unlock the contents of what resides behind the password encryption key. 
        ',
        'icon' => 'fa-lock'
    ],
    'App\Http\Controllers\Journal\JournalAchievementController'     => [
        'name' => 'Journal Achievements',
        'description' => ' 
            Do you enjoy keeping track of your achievements? Are you a fan of seeing your daily achievements fruition 
            on a daily basis? looking back over all the things that you might have achieved throughout the year? this 
            package allows you to be able to log your achievements and have them link up to a day in particular.
        ',
        'icon' => 'fa-trophy'
    ],
    'App\Http\Controllers\Journal\JournalController'                => [
        'name' => 'Journals',
        'description' => '
            If you enjoy keeping track of your daily life... then you will love what this offers. This package offers 
            you the ability to log your daily happenings, this is your online journal. You will be able to log your 
            overall day, and talk about the highest and lowest part of your day.
        ',
        'icon' => 'fa-calendar',
        'images' => [
            asset('assets/images/packages/journal/journal_package_image_1.png'),
            asset('assets/images/packages/journal/journal_package_image_2.png'),
            asset('assets/images/packages/journal/journal_package_image_3.png'),
        ]
    ],
    'App\Http\Controllers\Journal\JournalDreamController'           => [
        'name' => 'Dream Journals',
        'description' => '
            Everyone has dreams... right? it is not often that you remember it throughout the day, so this place is the 
            perfect place to be able to log your dreams... link it up to your day, ever wondered how much your dreams 
            could impact your daily performance? daily thoughts? daily mood? dabble into the journal dream logging and 
            be more self aware than you were prior.
        ',
        'icon' => 'fa-moon-o'
    ],
    'App\Http\Controllers\Project\ProjectController'                => [
        'name' => 'Projects',
        'description' => '
            If you are a fan of management, and you are needing a hub for being able to create projects for everything 
            that you are wanting to do, setting yourself up with a bunch of tasks grouped together, project by project.
        ',
        'icon' => 'fa-sticky-note',
        'images' => [
            asset('assets/images/packages/project/project_package_image_1.png'),
            asset('assets/images/packages/project/project_package_image_2.png'),
            asset('assets/images/packages/project/project_package_image_3.png'),
            asset('assets/images/packages/project/project_package_image_4.png'),
            asset('assets/images/packages/project/project_package_image_5.png'),
        ]
    ],
    'App\Http\Controllers\Project\ProjectSettingController'         => [
        'name' => 'Projects Settings',
        'description' => '',
        'icon' => 'fa-cog'
    ],
    'App\Http\Controllers\Project\ProjectUserContributorController' => [
        'name' => 'Project Contributors',
        'description' => '',
        'icon' => 'fa-users'
    ],
    'App\Http\Controllers\Store\StoreProductController'             => [
        'name' => 'Store Products',
        'description' => '',
        'icon' => ''
    ],
    'App\Http\Controllers\Store\StoreBasketController'              => [
        'name' => 'Store Basket',
        'description' => '',
        'icon' => ''
    ],
    'App\Http\Controllers\System\SystemChangelogController'         => [
        'name' => 'System Changelog',
        'description' => '',
        'icon' => ''
    ],
    'App\Http\Controllers\System\SystemController'                  => [
        'name' => 'System',
        'description' => '',
        'icon' => ''
    ],
    'App\Http\Controllers\System\SystemModuleAccessController'      => [
        'name' => 'System Module Access',
        'description' => '',
        'icon' => ''
    ],
    'App\Http\Controllers\System\SystemModuleController'            => [
        'name' => 'System Module',
        'description' => '',
        'icon' => ''
    ],
    'App\Http\Controllers\Task\TaskChecklistController'             => [
        'name' => 'Task Checklists',
        'description' => '',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskChecklistItemController'         => [
        'name' => 'Task Checklist Items',
        'description' => '',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskCommentController'               => [
        'name' => 'Task Comments',
        'description' => '',
        'icon' => 'fa-comment'
    ],
    'App\Http\Controllers\Task\TaskController'                      => [
        'name' => 'Tasks',
        'description' => '',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskFileController'                  => [
        'name' => 'Task Files',
        'description' => '',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskIssueTypeController'             => [
        'name' => 'Task Issue Types',
        'description' => '',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskLogController'                   => [
        'name' => 'Task Logging',
        'description' => '',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskPriorityController'              => [
        'name' => 'Task Priorities',
        'description' => '',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskStatusController'                => [
        'name' => 'Task Statuses',
        'description' => '',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskWatcherUserController'           => [
        'name' => 'Task Watchers',
        'description' => '',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\TimeLog\TimeLogController' => [
        'name' => 'Timelogging',
        'description' => '
            Are you a fan of keeping track of all the work that you do? Are you a fan of being able to see where all 
            your time has gone? you will be given a month by month report which keeps track of all the tasks and 
            projects that you have worked on over the month. This package at the current moment in time is entirely 
            dependent on having the project/task package, as you need somewhere to be logging your time against in order 
            for this particular feature to work.
        ',
        'icon' => 'fa-tasks',
        'images' => [
            asset('assets/images/packages/time_log/timelog_package_image_1.png'),
            asset('assets/images/packages/time_log/timelog_package_image_2.png'),
            asset('assets/images/packages/time_log/timelog_package_image_3.png')
        ]
    ],
    'App\Http\Controllers\User\UserController'                      => [
        'name' => 'Users',
        'description' => '',
        'icon' => ''
    ],
    'App\Http\Controllers\User\UserDetailController'                => [
        'name' => 'User Details',
        'description' => '',
        'icon' => ''
    ],
    'App\Http\Controllers\User\UserSettingController'               => [
        'name' => 'User Settings',
        'description' => '',
        'icon' => ''
    ],
];
