<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Project\ProjectController;
use App\Http\Controllers\Web\Project\ProjectSettingController;
use App\Http\Controllers\Web\Project\ProjectDashboardController;

/*
|-------------------------------------------------------------------------------------------------------------------
| Project Routes
|-------------------------------------------------------------------------------------------------------------------
|
| The routes that will interact in some way with the projects. Viewing their project listings, viewing project tasks
| being able to add more, delete etc.
|
*/

//Route::get('/projects/dashboard',  [ProjectDashboardController::class, '_viewProjectsDashboardGet'])->name('projects.dashboard');
//Route::get('/projects',            [ProjectController::class, '_viewProjectsGet'])->name('projects.list');
//Route::get('/project/{code}',      [ProjectController::class, '_viewProjectGet'])->name('projects.project');
//Route::get('/project/delete/{id}', [ProjectController::class, '_deleteProjectGet'])->name('projects.project.delete');
//Route::get('/ajax/projects',       [ProjectController::class, '_ajaxViewProjectsGet'])->name('projects.list.ajax');
//Route::get( '/ajax/make/project',  [ProjectController::class, '_ajaxViewCreateProjectGet'])->name('projects.create.view');
//Route::post('/ajax/make/project',  [ProjectController::class, '_ajaxCreateProjectPost'])->name('projects.create.ajax');

/*
|-------------------------------------------------------------------------------------------------------------------
| Project Settings Route
|-------------------------------------------------------------------------------------------------------------------
|
| The routes that will interact with project settings in any form; allowing the user to manipulate projects in their
| way that benefits the way they want view them.
|
*/
//Route::get( '/projects/settings',          [ProjectSettingController::class, '_viewProjectsSettingsGet'])->name('projects.settings');
//Route::get( '/project/{code}/settings',    [ProjectSettingController::class, '_viewProjectSettingsGet'])->name('projects.project.settings');
//Route::post('/ajax/edit/project/settings', [ProjectSettingController::class, '_editProjectSettingsPost'])->name('projects.project.settings.edit');