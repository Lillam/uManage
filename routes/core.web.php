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
use App\Http\Controllers\Web\User\UserController;
use App\Http\Controllers\Web\System\SystemController;
use App\Http\Controllers\Web\Account\AccountController;
use App\Http\Controllers\Web\Journal\JournalController;
use App\Http\Controllers\Web\Project\ProjectController;
use App\Http\Controllers\Web\TimeLog\TimeLogController;
use App\Http\Controllers\Web\User\UserSettingController;
use App\Http\Controllers\Web\Project\Task\TaskController;
use App\Http\Controllers\Web\Store\StoreBasketController;
use App\Http\Controllers\Web\Store\StoreProductController;
use App\Http\Controllers\Web\System\SystemModuleController;
use App\Http\Controllers\Web\Journal\JournalDreamController;
use App\Http\Controllers\Web\Project\Task\TaskLogController;
use App\Http\Controllers\Web\Journal\JournalReportController;
use App\Http\Controllers\Web\TimeLog\TimeLogReportController;
use App\Http\Controllers\Web\Journal\JournalFinanceController;
use App\Http\Controllers\Web\Project\ProjectSettingController;
use App\Http\Controllers\Web\System\SystemChangelogController;
use App\Http\Controllers\Web\Project\Task\TaskReportController;
use App\Http\Controllers\Web\Journal\JournalDashboardController;
use App\Http\Controllers\Web\Project\ProjectDashboardController;
use App\Http\Controllers\Web\Project\Task\TaskCommentController;
use App\Http\Controllers\Web\Journal\JournalAchievementController;
use App\Http\Controllers\Web\Project\Task\TaskChecklistController;
use App\Http\Controllers\Web\Project\Task\TaskDashboardController;
use App\Http\Controllers\Web\Journal\JournalDreamDashboardController;
use App\Http\Controllers\Web\Project\Task\TaskChecklistItemController;
use App\Http\Controllers\Web\Journal\JournalFinanceDashboardController;


/*
|-----------------------------------------------------------------------------------------------------------------------
| Global Redirections
|-----------------------------------------------------------------------------------------------------------------------
|
| Here is where all the redirections will live. If there's ever a route that needs re-routing elsewhere, they will be
| placed here.
|
*/

Route::get('/', fn () => redirect()->action([UserController::class, '_viewUserDashboardGet']))
    ->name('user.dashboard');

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
    Route::get('/user/{id}', [UserController::class, '_viewUserGet'])->name('users.user');

    Route::get('/ajax/collapse', [UserSettingController::class, '_ajaxSetSidebarCollapsed'])
        ->name('user.settings.sidebar-collapse');

    Route::get('/ajax/theme', [UserSettingController::class, '_ajaxSetTheme'])
        ->name('user.settings.theme');

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Account Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The routes that will interact in some way with the accounts. Viewing their account listings, viewing the passwords
    | and more of the account.
    |
    */

    Route::get( '/accounts', [AccountController::class, '_viewAccountsGet'])->name('accounts');
    Route::get( '/accounts/{account}', [AccountController::class, '_viewAccountGet'])->name('accounts.account');
    Route::get( '/ajax/view/accounts', [AccountController::class, '_ajaxViewAccountsGet'])->name('accounts.ajax');
    Route::post('/ajax/make/accounts', [AccountController::class, '_ajaxMakeAccountsPost'])->name('accounts.create.ajax');
    Route::get( '/ajax/delete/accounts', [AccountController::class, '_ajaxDeleteAccountsGet'])->name('accounts.delete.ajax');
    Route::get( '/ajax/view/password', [AccountController::class, '_ajaxViewAccountsPasswordGet'])->name('accounts.password.view.ajax');

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Project Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The routes that will interact in some way with the projects. Viewing their project listings, viewing project tasks
    | being able to add more, delete etc.
    |
    */

    Route::get('/projects/dashboard',  [ProjectDashboardController::class, '_viewProjectsDashboardGet'])->name('projects.dashboard');
    Route::get('/projects',            [ProjectController::class, '_viewProjectsGet'])->name('projects.list');
    Route::get('/project/{code}',      [ProjectController::class, '_viewProjectGet'])->name('projects.project');
    Route::get('/project/delete/{id}', [ProjectController::class, '_deleteProjectGet'])->name('projects.project.delete');
    Route::get('/ajax/projects',       [ProjectController::class, '_ajaxViewProjectsGet'])->name('projects.list.ajax');
    Route::get( '/ajax/make/project',  [ProjectController::class, '_ajaxViewCreateProjectGet'])->name('projects.create.view');
    Route::post('/ajax/make/project',  [ProjectController::class, '_ajaxCreateProjectPost'])->name('projects.create.ajax');

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Project Settings Route
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The routes that will interact with project settings in any form; allowing the user to manipulate projects in their
    | way that benefits the way they want view them.
    |
    */
    Route::get( '/projects/settings',          [ProjectSettingController::class, '_viewProjectsSettingsGet'])->name('projects.settings');
    Route::get( '/project/{code}/settings',    [ProjectSettingController::class, '_viewProjectSettingsGet'])->name('projects.project.settings');
    Route::post('/ajax/edit/project/settings', [ProjectSettingController::class, '_editProjectSettingsPost'])->name('projects.project.settings.edit');

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Task Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | The routes that will interact in some way with the tasks. Viewing their tasks individually, modifying information
    | about such and more.
    |
    | ToDo - Move the Ajax routes into api instead.
    |
    */

    Route::get( '/tasks/dashboard',                     [TaskDashboardController::class, '_viewTasksDashboardGet'])->name('projects.tasks.dashboard');
    Route::get( '/tasks',                               [TaskController::class, '_viewTasksGet'])->name('projects.tasks');
    Route::get( '/task/{code}/{id}',                    [TaskController::class, '_viewTaskGet'])->name('projects.tasks.task');
    Route::get( '/delete/task/{code}/{id}',             [TaskController::class, '_deleteTaskGet']);
    Route::get( '/ajax/search/tasks',                   [TaskController::class, '_ajaxSearchTasksGet'])->name('projects.tasks.task.search.ajax');
    Route::get( '/ajax/tasks',                          [TaskController::class, '_ajaxViewTasksGet'])->name('projects.tasks.ajax');
    Route::get( '/ajax/create/task',                    [TaskController::class, '_ajaxViewCreateTaskGet'])->name('projects.tasks.create');
    Route::post('/ajax/create/task',                    [TaskController::class, '_ajaxCreateTaskPost'])->name('projects.tasks.task.create.ajax');
    Route::post('/ajax/task/edit',                      [TaskController::class, '_ajaxEditTaskPost'])->name('projects.tasks.task.edit.ajax');

    Route::get( '/ajax/tasks/report',                   [TaskReportController::class, '_ajaxViewTasksReportGet'])->name('projects.tasks.report.ajax');

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Task Comment Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | All Routes that are regarding interaction between the user and the comments of a task will be found within this
    | block up until the next title.
    |
    */

    Route::get( '/ajax/view/task/comments',             [TaskCommentController::class, '_ajaxViewTaskCommentsGet'])->name('projects.tasks.comment.list');
    Route::post('/ajax/make/task/comment',              [TaskCommentController::class, '_ajaxMakeTaskCommentPost'])->name('projects.tasks.comment.create');
    Route::post('/ajax/delete/task/comment',            [TaskCommentController::class, '_ajaxDeleteTaskCommentPost'])->name('projects.tasks.comment.delete');

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Task Checklist Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | All routes that are regarding interaction between the user and task checklists of a task. All routes of such will
    | be found within this block up until the next title.
    |
    */

    Route::post('/ajax/make/task/checklist',            [TaskChecklistController::class, '_ajaxMakeTaskChecklistPost'])->name('projects.tasks.task.checklists.create.ajax');
    Route::get( '/ajax/view/task/checklists',           [TaskChecklistController::class, '_ajaxViewTaskChecklistsGet']);
    Route::post('/ajax/edit/task/checklist',            [TaskChecklistController::class, '_ajaxEditTaskChecklistPost']);
    Route::post('/ajax/delete/task/checklist',          [TaskChecklistController::class, '_ajaxDeleteTaskChecklistPost']);
    Route::post('/ajax/edit/task_checklist/order',      [TaskChecklistController::class, '_ajaxEditTaskChecklistOrderPost']);
    Route::post('/ajax/edit/task_checklist/zipped',     [TaskChecklistController::class, '_ajaxEditTaskChecklistEditZipStatus']);

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Task Checklist Item Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | All routes that are regarding interaction between the user and the task checklist items of a task and checklist.
    | All routes of such will be fond within this block up until the next title.
    |
    */

    Route::post('/ajax/make/task/checklist_item',       [TaskChecklistItemController::class, '_ajaxMakeTaskChecklistItemPost'])->name('projects.tasks.task.checklist.checklist_item.create.ajax');
    Route::get( '/ajax/view/task/checklist_items',      [TaskChecklistItemController::class, '_ajaxViewTaskChecklistItemsGet']);
    Route::post('/ajax/check/task_checklist_item',      [TaskChecklistItemController::class, '_ajaxCheckTaskChecklistItemPost']);
    Route::post('/ajax/edit/task_checklist_item',       [TaskChecklistItemController::class, '_ajaxEditTaskChecklistItemPost']);
    Route::post('/ajax/delete/task_checklist_item',     [TaskChecklistItemController::class, '_ajaxDeleteTaskChecklistItemPost']);
    Route::post('/ajax/edit/task_checklist_item/order', [TaskChecklistItemController::class, '_ajaxEditTaskChecklistItemOrderPost']);

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Task Log Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | All routes that are regarding interaction between the user and the system on anything task related, all task logs
    | will be handled within this block up until the next title block.
    |
    */

    Route::get('/task-activity',                        [TaskLogController::class, '_viewTaskLogActivityGet'])->name('project.tasks.activity');
    Route::get('/ajax/view/task/logs',                  [TaskLogController::class, '_ajaxViewTaskLogsGet']);
    Route::get('/ajax/view/task_log_activity',          [TaskLogController::class, '_ajaxViewTaskLogActivityGet']);

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Daily Journal Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | All Routes that are regarding interaction between the user and the daily journals. All routes of such will be
    | found within this block, up until the next title block.
    |
    */

    Route::get('/journals/calendar',                    [JournalController::class, '_viewJournalsGet'])->name('journals.calendar');
    Route::get('/journals/dashboard',                   [JournalDashboardController::class, '_viewJournalsDashboardGet'])->name('journals.dashboard');
    Route::get('/ajax/view/journals',                   [JournalController::class, '_ajaxViewJournalsGet'])->name('journals.ajax');
    Route::get('/journal/{date}',                       [JournalController::class, '_viewJournalGet'])->name('journals.journal');
    Route::post('/ajax/journal/edit',                   [JournalController::class, '_ajaxEditJournalPost'])->name('journals.journal.edit');
    Route::post('/ajax/delete/journal',                 [JournalController::class, '_ajaxDeleteJournalPost'])->name('journals.journal.delete');
    Route::get('/journals/report',                      [JournalReportController::class, '_viewJournalsReportGet'])->name('journals.report');
    Route::get('/ajax/view/journals/report',            [JournalReportController::class, '_ajaxViewJournalsReportGet'])->name('journals.report.ajax');

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Daily Journal Achievement Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | All Routes that are regarding interaction between the user and the journal achievements. All routes of such will
    | be found within this block up until the next title block
    |
    */

    Route::get( '/ajax/view/journal/achievements',      [JournalAchievementController::class, '_ajaxViewJournalAchievementsGet']);
    Route::post('/ajax/add/journal/achievement',        [JournalAchievementController::class, '_ajaxMakeJournalAchievementPost']);
    Route::post('/ajax/edit/journal/achievement',       [JournalAchievementController::class, '_ajaxEditJournalAchievementPost']);
    Route::post('/ajax/delete/journal/achievement',     [JournalAchievementController::class, '_ajaxDeleteJournalAchievementPost']);

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Dream Journal Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | All routes that are regarding interaction between the user and the dream journals. All routes of such will be
    | found within this block up until the next title block.
    |
    */

    Route::get('/dreams/journals/dashboard',            [JournalDreamDashboardController::class, '_viewJournalsDreamsDashboardGet'])->name('journals.dreams.dashboard');
    Route::get('/dreams/journals/calendar',             [JournalDreamController::class, '_viewJournalDreamsGet'])->name('journals.dreams.calendar');
    Route::get('/ajax/view/dreams/journals',            [JournalDreamController::class, '_ajaxViewJournalDreamsGet'])->name('journals.dreams.calendar.ajax');
    Route::get('/dreams/journals/{date}',               [JournalDreamController::class, '_viewJournalDreamGet'])->name('journals.dreams.dream');
    Route::post('/ajax/journal_dream/edit',             [JournalDreamController::class, '_editJournalDreamPost']);
    Route::post('/ajax/delete/journal_dream',           [JournalDreamController::class, '_ajaxDeleteJournalDreamPost']);

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Finance Journal Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | All routes that are regarding interaction between the user and the finance journals. All routes of such will be
    | found within this block up until the next title block.
    |
    */

    Route::get('/finances/journals/dashboard',          [JournalFinanceDashboardController::class, '_viewJournalsFinancesDashboardGet'])->name('journals.finances.dashboard');
    Route::get('/finance/journals/calendar',            [JournalFinanceController::class, '_viewJournalFinancesGet'])->name('journals.finances.calendar');
    Route::get('/ajax/view/finances/journals',          [JournalFinanceController::class, '_ajaxViewJournalFinancesGet']);

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Time Log Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | All Routes that are regarding interaction between the user and time logging. All routes of such will be found
    | within this block up until the next title block.
    |
    */

    Route::get( '/time-logs/calendar',                  [TimeLogController::class, '_viewTimeLogCalendarGet'])->name('time-logs.calendar');
    Route::get( '/ajax/view/time-logs',                 [TimeLogController::class, '_ajaxViewTimeLogsGet'])->name('time-logs.ajax');
    Route::get( '/ajax/view/time-logs-calendar',        [TimeLogController::class, '_ajaxViewTimeLogsCalendarGet'])->name('time-logs.calendar.ajax');
    Route::post('/ajax/make/time-log',                  [TimeLogController::class, '_ajaxMakeTimeLogPost'])->name('time-log.create.ajax');
    Route::get( '/ajax/delete/time-log',                [TimeLogController::class, '_ajaxDeleteTimeLogGet'])->name('time-log.delete.ajax');

    Route::get( '/time-log/report',                     [TimeLogReportController::class, '_viewTimeLogReportGet'])->name('time-logs.report');
    Route::get( '/ajax/view/time-log-report',           [TimeLogReportController::class, '_ajaxViewTimeLogReportGet'])->name('time-logs.report.ajax');

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | System Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | All Routes that are regarding interaction between the user and the system, such as change logs, or storing their
    | data locally will be found within this block up until the next title.
    |
    */

    Route::get('/system/changelogs',            [SystemChangelogController::class, '_viewSystemChangelogsGet'])->name('system.changelogs');
    Route::get('/system/changelogs/edit/{id?}', [SystemChangelogController::class, '_editSystemChangelogGet'])->name('system.changelogs.edit');
    Route::get('/system/changelogs/{id}',       [SystemChangelogController::class, '_viewSystemChangelogGet']);
    Route::get('/system/store/all',             [SystemController::class, '_storeAllModulesLocally'])->name('system.store');
    Route::get('/system/perform',               [SystemController::class, '_performRandomJob']);
    Route::get('/system/emojis',                [SystemController::class, '_getSummernoteEmojis'])->name('system.emojis');

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Store Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | All routes that are regarding interaction between the user and the store. All routes of such will be found within
    | this block, up until the next title block.
    |
    */

    Route::get('/store/products',                [StoreProductController::class, '_viewStoreProductsGet'])->name('store.products');
    Route::get('/store/product/{name}',          [StoreProductController::class, '_viewStoreProductGet'])->name('store.products.product');
    Route::get('/store/basket',                  [StoreBasketController::class, '_viewStoreBasketGet'])->name('store.basket');
    Route::get('/store/basket/add/{product}',    [StoreBasketController::class, '_addToStoreBasketGet'])->name('store.basket.add');
    Route::get('/store/basket/remove/{product}', [StoreBasketController::class, '_removeFromStoreBasketGet'])->name('store.basket.remove');

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Miscellaneous Routes
    |-------------------------------------------------------------------------------------------------------------------
    |
    | Any route that doesn't necessarily fall into a category of a module. non-permanent routes could be located and
    | situated here.
    |
    */

    Route::get('/test', [SystemController::class, '_performRandomJob']);
});