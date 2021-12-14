<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\System\SystemChangelog;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;

class SystemChangelogController extends Controller
{
    /**
    * SystemChangelogController constructor.
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * @param Request $request
    * @return Application|Factory|View
    */
    public function _viewSystemChangelogsGet(Request $request): Application|Factory|View
    {
        $this->vs->set('title', '- System Changelogs')
                 ->set('current_page', 'system.changelogs');

        return view('system.system_changelog.view_system_changelogs');
    }

    /**
    * This method is utilised specifically for being able to view the system changelogs singularly that are in the
    * system, and if one doesn't exist, then we are going to abort to a 404; because it doesn't exist, rather than
    * just showing the page that will show the user that nothing exists.
    *
    * @param Request $request
    * @param $id
    * @return Application|Factory|View
    */
    public function _viewSystemChangelogGet(Request $request, $id): Application|Factory|View
    {
        $system_changelog = SystemChangelog::where('id', '=', $id)->first();

        if (! $system_changelog instanceof SystemChangelog)
            abort(404);

        $this->vs->set('title', '- Add System Changelog')
                 ->set('current_page', 'system.changelogs');

        return view('system.system_changelog.view_system_changelog', compact(
            'system_changelog'
        ));
    }

    /**
    * @param Request $request
    * @param null $id
    * @return Application|Factory|View
    */
    public function _editSystemChangelogGet(Request $request, $id = null): Application|Factory|View
    {
        // if we have a string from the url, and the string is not equatable to "new"... or if we have an integer that
        // doesn't have something in the system then we are in both instances, going to return a 404 response so that
        // the system will stop there; this will be a standard rather than throwing the user back to the previous page
        // this will be to stop the page having to load some more database assets... and give the power to the user
        // in order for figuring out where they want to go to next...
        if (
            ((int) $id === 0 && $id !== 'new') ||
            ((int) $id > 0 && ! $system_changelog instanceof SystemChangelog)
        ) abort(404);

        $this->vs->set('title', '- Edit System Changelog')
                 ->set('current_page', 'system.changelogs');

        return view('system.system_changelog.edit_system_changelog');
    }
}