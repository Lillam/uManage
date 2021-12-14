<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class SystemModuleController extends Controller
{
    /**
    * SystemModuleController constructor.
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * @param Request $request
    * @return Application|Factory|View
    */
    public function _viewSystemModuleDashboardGet(Request $request): Application|Factory|View
    {
        $this->vs->set('title', 'Apps')
                 ->set('has_sidebar', false);

        $system_modules = $this->vs->get('user')->system_modules;

        return view('system.system_modules.system_modules_dashboard', compact(
            'system_modules'
        ));
    }
}
