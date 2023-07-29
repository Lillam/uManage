<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, DispatchesJobs;

    public function __construct()
    {

    }

    /**
     * Respond to the API call in the form of json... this is a utility method which stops the need for invoking
     * response, and just simply returning $this->respond subsequently reducing the amount of code.
     *
     * @param mixed $data
     * @return JsonResponse
     */
    protected function respond(mixed $data): JsonResponse
    {
        return response()->json($data);
    }

    /**
     * @param string $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters): mixed
    {
        dd('here');

        // attempt to call the method that the developer would have opted to call, and if that didn't work then we can
        // simply universalise all the fallbacks immediately to be responding in the form of json, letting the user
        // know that something had gone wrong, this way we don't ever have to perform any attempt to catch in the
        // methods singularly.
        try {
            return $this->$method(...$parameters);
        }

        // throw the error that we had caught and just dump it into the form of a json string that the frontend or api
        // caller would be able to understand.
        catch (Throwable $exception) {
            return $this->respond([ 'error' => $exception->getMessage() ]);
        }
    }
}