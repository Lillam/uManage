<?php

namespace App\Http\Controllers\Web\System;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use Illuminate\Contracts\Foundation\Application;

class SystemModuleController extends Controller
{
    /**
    * @param Request $request
    * @return Application|Factory|View
    */
    public function _viewSystemModuleDashboardGet(Request $request): Application|Factory|View
    {
        $this->vs->set('title', 'Apps')
                 ->set('hasSidebar', false);

        $system_modules = $this->vs->get('user')->system_modules;

        return view('system.system_modules.system_modules_dashboard', compact(
            'system_modules'
        ));
    }
}
