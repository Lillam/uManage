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
use App\Http\Controllers\Timelog\TimelogController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Task\TaskReportController;
use App\Http\Controllers\Task\TaskCommentController;
use App\Http\Controllers\Store\StoreBasketController;
use App\Http\Controllers\Store\StoreProductController;
use App\Http\Controllers\Task\TaskChecklistController;
use App\Http\Controllers\System\SystemModuleController;
use App\Http\Controllers\Journal\JournalDreamController;
use App\Http\Controllers\Journal\JournalReportController;
use App\Http\Controllers\Timelog\TimelogReportController;
use App\Http\Controllers\Journal\JournalFinanceController;
use App\Http\Controllers\Project\ProjectSettingController;
use App\Http\Controllers\System\SystemChangelogController;
use App\Http\Controllers\Task\TaskChecklistItemController;
use App\Http\Controllers\Journal\JournalAchievementController;

// initial predefined routes for the user to be able to attempt to access the system.
Route::get( '/login',  [UserController::class, '_viewUserLoginGet']);
Route::post('/login',  [UserController::class, '_viewUserLoginPost']);
Route::get( '/logout', [UserController::class, '_userLogout']);

Route::get('/', fn () => redirect()->action([UserController::class, '_viewUserDashboardGet']));

Route::group(['middleware' => ['auth', 'auth_user', 'module_check']], function () {
    // App Routes
    Route::get('/apps',                                 [SystemModuleController::class, '_viewSystemModuleDashboardGet']);

    // User Routes
    Route::get('/dashboard',                            [UserController::class, '_viewUserDashboardGet']);
    Route::get('/users',                                [UserController::class, '_viewUsersGet']);
    Route::get('/user/{id}',                            [UserController::class, '_viewUserGet']);

    // Project Routes
    Route::get('/projects',                             [ProjectController::class, '_viewProjectsGet']);
    Route::get('/project/{code}',                       [ProjectController::class, '_viewProjectGet']);
    Route::get('/project/delete/{id}',                  [ProjectController::class, '_deleteProjectGet']);
    Route::get('/ajax/projects',                        [ProjectController::class, '_ajaxViewProjectsGet']);
    Route::get( '/ajax/make/project',                   [ProjectController::class, '_ajaxViewCreateProjectGet']);
    Route::post('/ajax/make/project',                   [ProjectController::class, '_ajaxCreateProjectPost']);

    // Project Settings Routes
    Route::get( '/project/{code}/settings',             [ProjectSettingController::class, '_viewProjectSettingsGet']);
    Route::post('/ajax/edit/project/settings',          [ProjectSettingController::class, '_editProjectSettingsPost']);

    // Task Routes
    Route::get( '/tasks',                               [TaskController::class, '_viewTasksGet']);
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
    Route::get('/journals',                             [JournalController::class, '_viewJournalsGet']);
    Route::get('/ajax/view/journals',                   [JournalController::class, '_ajaxViewJournalsGet']);
    Route::get('/journal/{date}',                       [JournalController::class, '_viewJournalGet']);
    Route::post('/ajax/journal/edit',                   [JournalController::class, '_ajaxEditJournalPost']);
    Route::post('/ajax/delete/journal',                 [JournalController::class, '_ajaxDeleteJournalPost']);

    Route::get('/journals/report',                      [JournalReportController::class, '_viewJournalsReportGet']);
    Route::get('/ajax/view/journals/report',            [JournalReportController::class, '_ajaxViewJournalsReportGet']);

    Route::get( '/ajax/view/journal/achievements',      [JournalAchievementController::class, '_ajaxViewJournalAchievementsGet']);
    Route::post('/ajax/add/journal/achievement',        [JournalAchievementController::class, '_ajaxMakeJournalAchievementPost']);
    Route::post('/ajax/edit/journal/achievement',       [JournalAchievementController::class, '_ajaxEditJournalAchievementPost']);
    Route::post('/ajax/delete/journal/achievement',     [JournalAchievementController::class, '_ajaxDeleteJournalAchievementPost']);

    // Dream Journals
    Route::get('/dreams/journals',                      [JournalDreamController::class, '_viewJournalDreamsGet']);
    Route::get('/ajax/view/dreams/journals',            [JournalDreamController::class, '_ajaxViewJournalDreamsGet']);
    Route::get('/dreams/journals/{date}',               [JournalDreamController::class, '_viewJournalDreamGet']);
    Route::post('/ajax/journal_dream/edit',             [JournalDreamController::class, '_editJournalDreamPost']);
    Route::post('/ajax/delete/journal_dream',           [JournalDreamController::class, '_ajaxDeleteJournalDreamPost']);

    Route::get('/finance/journals',                     [JournalFinanceController::class, '_viewJournalFinancesGet']);
    Route::get('/ajax/view/finances/journals',          [JournalFinanceController::class, '_ajaxViewJournalFinancesGet']);

    // Timelogging
    Route::get( '/timelog',                             [TimelogController::class, '_viewTimelogCalendarGet']);
    Route::get( '/ajax/view/timelogs',                  [TimelogController::class, '_ajaxViewTimelogsGet']);
    Route::get( '/ajax/view/timelogs_calendar',         [TimelogController::class, '_ajaxViewTimelogsCalendarGet']);
    Route::post('/ajax/make/timelog',                   [TimelogController::class, '_ajaxMakeTimelogPost']);
    Route::get( '/ajax/delete/timelog',                 [TimelogController::class, '_ajaxDeleteTimelogGet']);

    Route::get( '/timelog/report',                      [TimelogReportController::class, '_viewTimelogReportGet']);
    Route::get( '/ajax/view/timelog_report',            [TimelogReportController::class, '_ajaxViewTimelogReportGet']);

    // Account Managing
    Route::get( 'accounts',                             [AccountController::class, '_viewAccountsGet']);
    Route::get( 'ajax/view/accounts',                   [AccountController::class, '_ajaxViewAccountsGet']);
    Route::post('ajax/make/accounts',                   [AccountController::class, '_ajaxMakeAccountsPost']);
    Route::get( 'ajax/delete/accounts',                 [AccountController::class, '_ajaxDeleteAccountsGet']);
    Route::get( 'ajax/view/password',                   [AccountController::class, '_ajaxViewAccountsPasswordGet']);

    // System routes:
    Route::get('/system/changelogs',                    [SystemChangelogController::class, '_viewSystemChangelogsGet']);
    Route::get('/system/changelogs/{id}',               [SystemChangelogController::class, '_viewSystemChangelogGet']);
    Route::get('/system/changelogs/edit/{id?}',         [SystemChangelogController::class, '_editSystemChangelogGet']);
    Route::get('/system/store/all',                     [SystemController::class, '_storeAllModulesLocally']);
    Route::get('/system/perform',                       [SystemController::class, '_performRandomJob']);
    Route::get('/system/emojis',                        [SystemController::class, '_getSummernoteEmojis']);

    // Store Pages
    Route::get('/store/products',                       [StoreProductController::class, '_viewStoreProductsGet']);
    Route::get('/store/product/{name}',                 [StoreProductController::class, '_viewStoreProductGet']);

    Route::get('/store/basket',                         [StoreBasketController::class, '_viewStoreBasketGet']);
    Route::get('/store/basket/add/{product}',           [StoreBasketController::class, '_addToStoreBasketGet']);
    Route::get('/store/basket/remove/{product}',        [StoreBasketController::class, '_removeFromStoreBasketGet']);

    Route::get('/test',                                 [SystemController::class, '_performRandomJob']);
});