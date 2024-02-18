<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Handler extends ExceptionHandler
{
    /**
    * A list of the exception types that are not reported.
    *
    * @var array
    */
    protected $dontReport = [
        //
    ];

    /**
    * A list of the inputs that are never flashed for validation exceptions.
    *
    * @var array
    */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
    * Report or log an exception.
    *
    * @param  Throwable $exception
    * @return mixed|void
    * @throws Throwable
    */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
    * Render an exception into an HTTP response; if we are on the live environment, then we are just going to want to
    * return all errors into a none readable page, and just throw something that's very standard, as we aren't going
    * to want to give anyone any insight into the system markup; and thus, with this we are just going to return an
    * error page that allows the user to report the issue at hand up; rather than being able to read it for themselves
    * denying specific users some intel...
    *
    * @param  Request $request
    * @param  Throwable $exception
    * @return JsonResponse|Response|SymfonyResponse|View
    * @throws Throwable
    */
    public function render($request, Throwable $exception): JsonResponse|Response|SymfonyResponse|View
    {
        if (
            App::environment(config('app.dev.environments')) ||
            $exception instanceof NotFoundHttpException ||
            $exception instanceof HttpException
        ) {
            return parent::render($request, $exception);
        }

        return response()->view('errors.oops', [
            // potentially pass some information onto the error page regarding the exception right here, so that there
            // can be some insight for the user at hand to be able to better report the page up.
            // potentially could even store the exception in an encrypted cache, to be able to deal with the exception
            // upon the user wanting to submit it above...
        ], 503);
    }
}
