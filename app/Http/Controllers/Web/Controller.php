<?php

namespace App\Http\Controllers\Web;

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
    protected ViewService $vs;

    /**
    * Controller constructor. - utilised for applying all the necessary pieces that the controllers will be needing on
    * a global scale.
    *
    * @return void
    */
    public function __construct()
    {
        $this->vs = app('vs')->set('user', Auth::user());
    }
}
