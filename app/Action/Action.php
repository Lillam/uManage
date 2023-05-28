<?php

namespace App\Action;

use Illuminate\Http\Request;

abstract class Action
{
    protected Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    abstract public function handle();
}