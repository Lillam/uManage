<?php

namespace App\Exceptions\Solutions;

use Facade\IgnitionContracts\Solution;

class UrlGeneratorSolution implements Solution
{
    /**
    * If we have opted to construct, then we are simply going to be passing in the exception itself, so that we can
    * acquire some information about it... so we are going to be able to call a variety of methods that could be
    * utilised for finding some links from google or even stack overflow
    *
    * UrlGeneratorSolution constructor.
    */
    public function __construct()
    {

    }

    public function getSolutionTitle(): string
    {
        return 'The above mentioned route, is not found within the web.php file.';
    }

    public function getSolutionDescription(): string
    {
        return 'This can be solved via the following methods: php artisan zip, php artisan cache:clear, php artisan ' .
            'route:clear or even check whether or not the route above is defined within web.php';
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Routing' => 'https://laravel.com/docs/8.x/routing#view-routes',
            'Caching' => 'https://laravel.com/docs/8.x/controllers#route-caching'
        ];
    }
}