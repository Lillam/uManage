<?php

namespace App\Http\Controllers;

use App\Services\View\ViewService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
    * @var ViewService $vs | vs for ViewService
    */
    protected $vs;

    /**
    * Controller constructor.
    *
    * @return void
    */
    public function __construct()
    {
        $this->vs = app('vs');
        $this->vs->set('user', Auth::user());
    }
}
