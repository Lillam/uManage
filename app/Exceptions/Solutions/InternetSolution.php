<?php

namespace App\Exceptions\Solutions;

use Facade\IgnitionContracts\Solution;
use App\Providers\ExceptionSolutionProvider;

class InternetSolution implements Solution
{
    /**
    * @var ExceptionSolutionProvider
    */
    private ExceptionSolutionProvider $esp;

    /**
    * Upon the construction of this class, we are going to potentially pass in the exception so that we are going to be
    * able to reference a variety of things wrong with it, and highlighting what the searches are going to be.
    *
    * InternetSolutions constructor.
    *
    * @param ExceptionSolutionProvider $esp
    * @return void
    */
    public function __construct(ExceptionSolutionProvider $esp)
    {
        $this->esp = $esp;
    }

    /**
    * @return string
    */
    public function getSolutionTitle(): string
    {
        return 'We have done a swift internet search based on the error code provided';
    }

    /**
    * @return string
    */
    public function getSolutionDescription(): string
    {
        return 'Based on the internet search that has been executed by the SolutionProvider, there has been some' .
               ' links that has been returned, you are going to want to take a read over these to see if there is a ' .
               ' solution to your problems.';
    }

    /**
     * This method is going to be taking the internet solutions, mapping over them and then dumping everything into a
     * returnable array to the user. this takes a variety of edge-cases from what had come back from get file contents
     * on a url string... and mapped into an array that takes the url, and then makes a readable string from the url
     *
    * @return array
    */
    public function getDocumentationLinks(): array
    {
        $this->esp->get('internet_solutions')->map(function ($value) use (&$internet_solutions) {
            $link_text = explode('/', $value);
            $internet_solutions[ucwords(preg_replace(
                '/\&(.*)/', '', str_replace(
                    '-',
                    ' ',
                    substr(end($link_text), 0, 50)
                )
            ))] = ltrim($value, 'url?q=/');
        }); return $internet_solutions;
    }
}