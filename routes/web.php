<?php

/*
|-----------------------------------------------------------------------------------------------------------------------
| Web Routes
|-----------------------------------------------------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These routes are loaded by the RouteServiceProvider
| within a group which contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Task\TaskLogController;
use App\Http\Controllers\System\SystemController;
use App\Http\Controllers\Journal\JournalController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\TimeLog\TimeLogController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Task\TaskReportController;
use App\Http\Controllers\Task\TaskCommentController;
use App\Http\Controllers\User\UserSettingController;
use App\Http\Controllers\Store\StoreBasketController;
use App\Http\Controllers\Store\StoreProductController;
use App\Http\Controllers\Task\TaskChecklistController;
use App\Http\Controllers\Task\TaskDashboardController;
use App\Http\Controllers\System\SystemModuleController;
use App\Http\Controllers\Journal\JournalDreamController;
use App\Http\Controllers\Journal\JournalReportController;
use App\Http\Controllers\TimeLog\TimeLogReportController;
use App\Http\Controllers\Journal\JournalFinanceController;
use App\Http\Controllers\Project\ProjectSettingController;
use App\Http\Controllers\System\SystemChangelogController;
use App\Http\Controllers\Task\TaskChecklistItemController;
use App\Http\Controllers\Journal\JournalDashboardController;
use App\Http\Controllers\Project\ProjectDashboardController;
use App\Http\Controllers\Journal\JournalAchievementController;
use App\Http\Controllers\Journal\JournalDreamDashboardController;
use App\Http\Controllers\Journal\JournalFinanceDashboardController;


/*
|-----------------------------------------------------------------------------------------------------------------------
| Global Redirections
|-----------------------------------------------------------------------------------------------------------------------
|
| Here is where all the redirections will live. If there's ever a route that needs re-routing elsewhere, they will be
| placed here.
|
*/

Route::get('/', fn () => redirect()->action([UserController::class, '_viewUserDashboardGet']));

/*
|-----------------------------------------------------------------------------------------------------------------------
| Unauthenticated Routes
|-----------------------------------------------------------------------------------------------------------------------
|
| Here is where all the routes for unauthenticated users will reside. These routes can be accessed by anyone wanting
| to interact with the system without actually being signed in.
|
*/

Route::get( '/login',  [UserController::class, '_viewUserLoginGet'])->name('user.login');
Route::post('/login',  [UserController::class, '_viewUserLoginPost'])->name('user.login');
Route::get( '/logout', [UserController::class, '_userLogout'])->name('user.logout');

/*
|-----------------------------------------------------------------------------------------------------------------------
| Authenticated Routes
|-----------------------------------------------------------------------------------------------------------------------
|
| Here is where all the routes where the user is needed to be signed in order to access them; the following middleware
| will be doing the following:
| - auth: checking if the user is logged in
| - auth_user: after the user has been authenticated, apply everything the user will need to utilise the application.
| - module_check: checking to see if the user has the module against their user profile.
|
*/

Route::group(['middleware' => ['auth', 'auth_user', 'module_check']], function () {
    // App Routes
    Route::get('/apps', [SystemModuleController::class, '_viewSystemModuleDashboardGet']);

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | User Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The routes that will interact in some way with the user; viewing their dashboards, viewing their user page,
    | being able to change their password, view their module access and more.
    |
    */

    Route::get('/dashboard', [UserController::class, '_viewUserDashboardGet'])->name('user.dashboard');
    Route::get('/users',     [UserController::class, '_viewUsersGet']);
    Route::get('/user/{id}', [UserController::class, '_viewUserGet']);

    Route::get('/ajax/collapse', [UserSettingController::class, '_ajaxSetSidebarCollapsed'])
        ->name('user.settings.sidebar-collapse');

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Project Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The routes that will interact in some way with the projects. Viewing their project listings, viewing project tasks
    | being able to add more, delete etc.
    |
    */

    Route::get('/projects/dashboard',          [ProjectDashboardController::class, '_viewProjectsDashboardGet'])->name('projects.dashboard');
    Route::get('/projects',                    [ProjectController::class, '_viewProjectsGet'])->name('projects.list');
    Route::get('/project/{code}',              [ProjectController::class, '_viewProjectGet']);
    Route::get('/project/delete/{id}',         [ProjectController::class, '_deleteProjectGet']);
    Route::get('/ajax/projects',               [ProjectController::class, '_ajaxViewProjectsGet']);
    Route::get( '/ajax/make/project',          [ProjectController::class, '_ajaxViewCreateProjectGet']);
    Route::post('/ajax/make/project',          [ProjectController::class, '_ajaxCreateProjectPost']);

    // Project Settings Routes
    Route::get('/projects/settings',           [ProjectSettingController::class, '_viewProjectsSettingsGet'])->name('projects.settings');
    Route::get( '/project/{code}/settings',    [ProjectSettingController::class, '_viewProjectSettingsGet'])->name('projects.project.settings');
    Route::post('/ajax/edit/project/settings', [ProjectSettingController::class, '_editProjectSettingsPost']);

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Task Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The routes that will interact in some way with the tasks. Viewing their tasks individually, modifying information
    | about such and more.
    |
    */

    Route::get('/tasks/dashboard',                      [TaskDashboardController::class, '_viewTasksDashboardGet'])->name('projects.tasks.dashboard');
    Route::get( '/tasks',                               [TaskController::class, '_viewTasksGet'])->name('projects.tasks');
    Route::get( '/task/{code}/{id}',                    [TaskController::class, '_viewTaskGet']);
    Route::get( '/delete/task/{code}/{id}',             [TaskController::class, '_deleteTaskGet']);
    Route::get( '/ajax/search/tasks',                   [TaskController::class, '_ajaxSearchTasksGet']);
    Route::get( '/ajax/tasks',                          [TaskController::class, '_ajaxViewTasksGet']);
    Route::get( '/ajax/create/task',                    [TaskController::class, '_ajaxViewCreateTaskGet']);
    Route::post('/ajax/create/task',                    [TaskController::class, '_ajaxCreateTaskPost']);
    Route::post('/ajax/task/edit',                      [TaskController::class, '_ajaxEditTaskPost']);

    Route::get('/ajax/tasks/report',                    [TaskReportController::class, '_ajaxViewTasksReportGet']);

    // Task Comments
    Route::get( '/ajax/view/task/comments',             [TaskCommentController::class, '_ajaxViewTaskCommentsGet']);
    Route::post('/ajax/make/task/comment',              [TaskCommentController::class, '_ajaxMakeTaskCommentPost']);
    Route::post('/ajax/delete/task/comment',            [TaskCommentController::class, '_ajaxDeleteTaskCommentPost']);

    // Task Checklists
    Route::post('/ajax/make/task/checklist',            [TaskChecklistController::class, '_ajaxMakeTaskChecklistPost']);
    Route::get( '/ajax/view/task/checklists',           [TaskChecklistController::class, '_ajaxViewTaskChecklistsGet']);
    Route::post('/ajax/edit/task/checklist',            [TaskChecklistController::class, '_ajaxEditTaskChecklistPost']);
    Route::post('/ajax/delete/task/checklist',          [TaskChecklistController::class, '_ajaxDeleteTaskChecklistPost']);
    Route::post('/ajax/edit/task_checklist/order',      [TaskChecklistController::class, '_ajaxEditTaskChecklistOrderPost']);
    Route::post('/ajax/edit/task_checklist/zipped',     [TaskChecklistController::class, '_ajaxEditTaskChecklistEditZipStatus']);

    // Task Checklist items
    Route::post('/ajax/make/task/checklist_item',       [TaskChecklistItemController::class, '_ajaxMakeTaskChecklistItemPost']);
    Route::get( '/ajax/view/task/checklist_items',      [TaskChecklistItemController::class, '_ajaxViewTaskChecklistItemsGet']);
    Route::post('/ajax/check/task_checklist_item',      [TaskChecklistItemController::class, '_ajaxCheckTaskChecklistItemPost']);
    Route::post('/ajax/edit/task_checklist_item',       [TaskChecklistItemController::class, '_ajaxEditTaskChecklistItemPost']);
    Route::post('/ajax/delete/task_checklist_item',     [TaskChecklistItemController::class, '_ajaxDeleteTaskChecklistItemPost']);
    Route::post('/ajax/edit/task_checklist_item/order', [TaskChecklistItemController::class, '_ajaxEditTaskChecklistItemOrderPost']);

    // Task Logs
    Route::get('/task-activity',                        [TaskLogController::class, '_viewTaskLogActivityGet']);
    Route::get('/ajax/view/task/logs',                  [TaskLogController::class, '_ajaxViewTaskLogsGet']);
    Route::get('/ajax/view/task_log_activity',          [TaskLogController::class, '_ajaxViewTaskLogActivityGet']);

    // Journals
    Route::get('/journals/calendar',                    [JournalController::class, '_viewJournalsGet'])->name('journals.calendar');
    Route::get('/journals/dashboard',                   [JournalDashboardController::class, '_viewJournalsDashboardGet'])->name('journals.dashboard');
    Route::get('/ajax/view/journals',                   [JournalController::class, '_ajaxViewJournalsGet'])->name('journals.ajax');
    Route::get('/journal/{date}',                       [JournalController::class, '_viewJournalGet'])->name('journals.journal');
    Route::post('/ajax/journal/edit',                   [JournalController::class, '_ajaxEditJournalPost'])->name('journals.journal.edit');
    Route::post('/ajax/delete/journal',                 [JournalController::class, '_ajaxDeleteJournalPost'])->name('journals.journal.delete');

    Route::get('/journals/report',                      [JournalReportController::class, '_viewJournalsReportGet'])->name('journals.report');
    Route::get('/ajax/view/journals/report',            [JournalReportController::class, '_ajaxViewJournalsReportGet'])->name('journals.report.ajax');

    Route::get( '/ajax/view/journal/achievements',      [JournalAchievementController::class, '_ajaxViewJournalAchievementsGet']);
    Route::post('/ajax/add/journal/achievement',        [JournalAchievementController::class, '_ajaxMakeJournalAchievementPost']);
    Route::post('/ajax/edit/journal/achievement',       [JournalAchievementController::class, '_ajaxEditJournalAchievementPost']);
    Route::post('/ajax/delete/journal/achievement',     [JournalAchievementController::class, '_ajaxDeleteJournalAchievementPost']);

    // Dream Journals
    Route::get('/dreams/journals/dashboard',            [JournalDreamDashboardController::class, '_viewJournalsDreamsDashboardGet'])->name('journals.dreams.dashboard');
    Route::get('/dreams/journals/calendar',             [JournalDreamController::class, '_viewJournalDreamsGet'])->name('journals.dreams.calendar');
    Route::get('/ajax/view/dreams/journals',            [JournalDreamController::class, '_ajaxViewJournalDreamsGet'])->name('journals.dreams.calendar.ajax');
    Route::get('/dreams/journals/{date}',               [JournalDreamController::class, '_viewJournalDreamGet']);
    Route::post('/ajax/journal_dream/edit',             [JournalDreamController::class, '_editJournalDreamPost']);
    Route::post('/ajax/delete/journal_dream',           [JournalDreamController::class, '_ajaxDeleteJournalDreamPost']);

    Route::get('/finances/journals/dashboard',          [JournalFinanceDashboardController::class, '_viewJournalsFinancesDashboardGet'])->name('journals.finances.dashboard');
    Route::get('/finance/journals/calendar',            [JournalFinanceController::class, '_viewJournalFinancesGet'])->name('journals.finances.calendar');
    Route::get('/ajax/view/finances/journals',          [JournalFinanceController::class, '_ajaxViewJournalFinancesGet']);

    // TimeLogging
    Route::get( '/time-logs/calendar',                   [TimeLogController::class, '_viewTimeLogCalendarGet'])->name('time-logs.calendar');
    Route::get( '/ajax/view/time-logs',                  [TimeLogController::class, '_ajaxViewTimeLogsGet']);
    Route::get( '/ajax/view/time-logs_calendar',         [TimeLogController::class, '_ajaxViewTimeLogsCalendarGet']);
    Route::post('/ajax/make/time-log',                   [TimeLogController::class, '_ajaxMakeTimeLogPost']);
    Route::get( '/ajax/delete/time-log',                 [TimeLogController::class, '_ajaxDeleteTimeLogGet']);

    Route::get( '/time-log/report',                      [TimeLogReportController::class, '_viewTimeLogReportGet'])->name('time-logs.report');
    Route::get( '/ajax/view/time-log-report',            [TimeLogReportController::class, '_ajaxViewTimeLogReportGet'])->name('time-logs.report.ajax');

    // Account Managing
    Route::get( 'accounts',                             [AccountController::class, '_viewAccountsGet'])->name('accounts');
    Route::get( 'ajax/view/accounts',                   [AccountController::class, '_ajaxViewAccountsGet']);
    Route::post('ajax/make/accounts',                   [AccountController::class, '_ajaxMakeAccountsPost']);
    Route::get( 'ajax/delete/accounts',                 [AccountController::class, '_ajaxDeleteAccountsGet']);
    Route::get( 'ajax/view/password',                   [AccountController::class, '_ajaxViewAccountsPasswordGet']);

    // System routes:
    Route::get('/system/changelogs',                    [SystemChangelogController::class, '_viewSystemChangelogsGet'])->name('system.changelogs');
    Route::get('/system/changelogs/{id}',               [SystemChangelogController::class, '_viewSystemChangelogGet']);
    Route::get('/system/changelogs/edit/{id?}',         [SystemChangelogController::class, '_editSystemChangelogGet']);
    Route::get('/system/store/all',                     [SystemController::class, '_storeAllModulesLocally'])->name('system.store');
    Route::get('/system/perform',                       [SystemController::class, '_performRandomJob']);
    Route::get('/system/emojis',                        [SystemController::class, '_getSummernoteEmojis']);

    // Store Pages
    Route::get('/store/products',                       [StoreProductController::class, '_viewStoreProductsGet'])->name('store.products');
    Route::get('/store/product/{name}',                 [StoreProductController::class, '_viewStoreProductGet']);

    Route::get('/store/basket',                         [StoreBasketController::class, '_viewStoreBasketGet']);
    Route::get('/store/basket/add/{product}',           [StoreBasketController::class, '_addToStoreBasketGet']);
    Route::get('/store/basket/remove/{product}',        [StoreBasketController::class, '_removeFromStoreBasketGet']);

    Route::get('/test',                                 [SystemController::class, '_performRandomJob']);
});