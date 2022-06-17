<?php

return [
    /*
    |-------------------------------------------------------------------------------------------------------------------
    | System Module Languages
    |-------------------------------------------------------------------------------------------------------------------
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
        'icon' => 'fa-moon'
    ],
    'App\Http\Controllers\Journal\JournalFinanceController'         => [
        'name'        => 'Finance Journals',
        'description' => '
            Everyone spends their money in a variety of ways; Journalise and visualise the way that you are spending
            your money. This package offers a dashboard to where your money goes, how much money goes on specific days 
            and you can begin analysing yourself on your spending to take better control of your money, or simple better
            see where it is going. 
        ',
        'icon'        => 'fa-dollar-sign'
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
        'description' => '
            Ability to edit and manipulate the visuals of projects, the name of the project, the display of the project
            whether you desire a kanban view of your projects or table oriented view. Depending on the rest of perks 
            that you might have applied from this module, you will be able to manipulate all regarding contributions,
            Commenting, checklist items, checklist grouping and more. 
        ',
        'icon' => 'fa-cog'
    ],
    'App\Http\Controllers\Project\ProjectUserContributorController' => [
        'name' => 'Project Contributors',
        'description' => '
            Your projects can have a multitude of contributors. Invite and add others to your projects and get help in 
            further developing yourself and your projects. Contributors can be applied to tasks within a project as well
            as applied to the project itself, meaning that anyone assigned as a contributor to a project will/can be 
            automatically assigned to every task within.
        ',
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
        'description' => '
            Feel as though Tasks just aren\'t enough to visualise everything you need to do? feel like one task 
            encompasses more than one problem? With this package you will be able to create a checklist of todos which 
            makes your todo list a bit more of a grouped thing; Create as many checklists for your tasks as they need 
            to get the job done!
        ',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskChecklistItemController'         => [
        'name' => 'Task Checklist Items',
        'description' => '
            Items for the checklists that allow you to create individual subsidiary tasks on your task that better aid 
            you in creating a more structured list of what needs to be achieved. The ability to create, update, delete
            and even reorganise the way in which these display to the frontend, fully customisable for you to get the 
            job done.
        ',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskCommentController'               => [
        'name' => 'Task Comments',
        'description' => '
            Collaborate on tasks with the ability to comment on tasks. You will be able to create comments, react with 
            emojis along with the ability to have conversational threads, replies and more. These count as task logs 
            which will work in tandem with all other task logging; which will also be visualised in the task log 
            dashboard and timeline.
        ',
        'icon' => 'fa-comment'
    ],
    'App\Http\Controllers\Task\TaskController'                      => [
        'name' => 'Tasks',
        'description' => '
            Your projects can have the possibility of adding tasks, A To Do list so to speak. A place where you can 
            visualise all the things that you may want to get out of a project. Depending on your further additions you 
            would be able to apply files, issues, statuses and more in order to give yourself some structure on your 
            goals. 
        ',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskFileController'                  => [
        'name' => 'Task Files',
        'description' => '
            When manipulating tasks you may find yourself wanting to collate ideas and add files to your tasks. Giving 
            you the ability to upload any file you desire, with the exempt executable files you will be able to 
            download, upload, delete and manipulate your uploads in any way you deem fit.
        ',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskIssueTypeController'             => [
        'name' => 'Task Issue Types',
        'description' => '
            When creating tasks it helps to categorise them. Be greeted with the default: [Bug, Feature, Research]
            along with the possibility of adding even more if you find that these are not enough. 
        ',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskLogController'                   => [
        'name' => 'Task Logging',
        'description' => '
            If you ever want to visualise what it is you get up, how much you get up or even reminding yourself what it 
            was that you got up. All amendments to your project/tasks will be logged accordingly. Logging will commence
            upon this being purchased. This feature will also give you access to a timeline of all logs, alongside a 
            dashboard to visualise the most recent changes.
        ',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskPriorityController'              => [
        'name' => 'Task Priorities',
        'description' => '
            You may find yourself wanting to have priorities when creating yourself tasks; by default you will have 
            access to [Lowest, Low, Medium, High, Highest] along with the ability to create, edit and delete any 
            priority you may desire in order for visualising your project.
        ',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskStatusController'                => [
        'name' => 'Task Statuses',
        'description' => '
            Somewhat of a standard, however you may assign statuses to tickets; if you are in a collaborative experience
            it will help to have a visual cue letting you know the status in which a ticket is in. out of the box you 
            will have access to: [ToDo, InProgress, Done, Archived]. And you will also be able to add more at your
            leisure along with creating, editing, and deleting any of the existing.
        ',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\Task\TaskWatcherUserController'           => [
        'name' => 'Task Watchers',
        'description' => '
            Should you find yourself in a situation you have contributors in your project; you can assign yourself as a 
            watcher or add the said user as a watcher and whomever has been added as a watcher can be notified of the 
            changes that have been made. Fully customisable on the parameters in which the user would be notified on 
            changes and to what frequency.
        ',
        'icon' => 'fa-tasks'
    ],
    'App\Http\Controllers\TimeLog\TimeLogController'                => [
        'name' => 'Time Logging',
        'description' => '
            Are you a fan of keeping track of all the work that you do? Are you a fan of being able to see where all 
            your time has gone? you will be given a month by month report which keeps track of all the tasks and 
            projects that you have worked on over the month. This package at the current moment in time is entirely 
            dependent on having the project/task package, as you need somewhere to be logging your time against in order 
            for this particular feature to work.
        ',
        'icon' => 'fa-tasks',
        'images' => [
            asset('assets/images/packages/time_log/time_log_package_image_1.png'),
            asset('assets/images/packages/time_log/time_log_package_image_2.png'),
            asset('assets/images/packages/time_log/time_log_package_image_3.png')
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