<?php

namespace App\Exceptions\Solutions;

use Spatie\Ignition\Contracts\Solution;

class BindingResolutionExceptionSolution implements Solution
{
    /**
    * @return string
    */
    public function getSolutionTitle(): string
    {
        return 'Could not find Binding';
    }

    /**
    * @return string
    */
    public function getSolutionDescription(): string
    {
        $this->getApplicationBindings();
        return 'You are trying to access a binding against the application that does not exist, via the utilisation of '
            .'**App::make(\'something\')**';
    }

    /**
    * @return array
    */
    public function getDocumentationLinks(): array
    {
        return [];
    }

    public function getApplicationBindings()
    {

    }
}