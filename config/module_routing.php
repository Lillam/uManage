<?php

return [
    /**
    |-------------------------------------------------------------------------------------------------------------------
    | Module Web Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The housing for all module (web) oriented routes; which are dedicated to their own file allowing simpler means and
    | clearer visibility.
    |
    */

    'web' => [
        'routes/modules/account/account.web.php',
        'routes/modules/apps/apps.web.php',
        'routes/modules/project/project.web.php',
        'routes/modules/project/task/task.web.php',
        'routes/modules/project/time_log/time_log.web.php',
        'routes/modules/store/store.web.php',
        'routes/modules/system/system.web.php',
        'routes/modules/user/user.web.php'
    ],

    /**
    |-------------------------------------------------------------------------------------------------------------------
    | Module Api Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The housing for all module (api) oriented routes; which are dedicated to their own file allowing simpler means and
    | clearer visibility.
    |
    */

    'api' => [
        'routes/modules/account/account.api.php',
        'routes/modules/apps/apps.api.php',
        'routes/modules/project/project.api.php',
        'routes/modules/project/task/task.api.php',
        'routes/modules/project/time_log/time_log.api.php',
        'routes/modules/store/store.api.php',
        'routes/modules/system/system.api.php',
        'routes/modules/user/user.api.php'
    ],

    /**
    |-------------------------------------------------------------------------------------------------------------------
    | Module Console Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The housing for all module (console) oriented routes; which are dedicated to their own file allowing simpler means
    | and clearer visibility.
    |
    */

    'console' => [
        'routes/modules/account/account.console.php',
        'routes/modules/apps/apps.console.php',
        'routes/modules/project/project.console.php',
        'routes/modules/project/task/task.console.php',
        'routes/modules/project/time_log/time_log.console.php',
        'routes/modules/store/store.console.php',
        'routes/modules/system/system.console.php',
        'routes/modules/user/user.console.php'
    ],

    /**
    |-------------------------------------------------------------------------------------------------------------------
    | Module Channel Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The housing for all module (channels) oriented routes; which are dedicated to their own file allowing simpler
    | means and clearer visibility.
    |
    */

    'channels' => [
        'routes/modules/account/account.channels.php',
        'routes/modules/apps/apps.channels.php',
        'routes/modules/project/project.channels.php',
        'routes/modules/project/task/task.channels.php',
        'routes/modules/project/time_log/time_log.channels.php',
        'routes/modules/store/store.channels.php',
        'routes/modules/system/system.channels.php',
        'routes/modules/user/user.channels.php'
    ]
];