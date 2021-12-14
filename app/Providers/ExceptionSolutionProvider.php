<?php

namespace App\Providers;

use Throwable;
use Illuminate\Support\Collection;
use Facade\IgnitionContracts\HasSolutionsForThrowable;

class ExceptionSolutionProvider implements HasSolutionsForThrowable
{
    /**
    * Setting up all the solution providers that we have in the system, and then this class here will decide what type
    * of solution we are going to dish up to the user...
    *
    * @var string[]
    */
    private $solutions = [
        // default
        'Internet'                   => \App\Exceptions\Solutions\InternetSolution::class,
        // custom
        'UrlGenerator'               => \App\Exceptions\Solutions\UrlGeneratorSolution::class,
        'RelationNotFoundException'  => \App\Exceptions\Solutions\RelationNotFoundExceptionSolution::class,
        'Container'                  => \App\Exceptions\Solutions\BindingResolutionExceptionSolution::class
    ];

    /**
    * @var
    */
    private $solution;

    /**
    * @var string[]|Collection
    */
    private $internet_solutions = [];

    /**
    * @var Throwable
    */
    private $throwable;

    /**
    * @var bool
    */
    private $internet_searching_enabled;

    /**
    * @var string
    */
    private $internet_searching_engine;

    /**
    * @var int
    */
    private $internet_searching_results;

    /**
    * @var string[]
    */
    private $internet_searching_engine_query_strings = [
       'google' => 'http://www.google.com/search?q='
    ];

    /**
    * @var string[]
    */
    private $internet_searching_pages;

    /**
    * Upon the instantiation of this ErrorSolutionProvider we are going to be setting some defaults, that can be found
    * in the config region for this particular package, this will be setting the search engine, whether or not it is
    * enabled or not... and where it's going to be getting the internet search from.
    *
    * ExceptionSolutionProvider constructor.
    * @return void
    */
    public function __construct()
    {
        $this->internet_searching_enabled = config('exceptions.internet_searching_enabled', false);
        $this->internet_searching_engine  = config('exceptions.internet_searching_engine',  'google');
        $this->internet_searching_results = config('exceptions.internet_searching_results', 5);
        $this->internet_searching_pages   = config('exceptions.internet_searching_pages',   false);
    }

    /**
    * Check whether or not the system has a solution for the Throwable/Exception is coming back from the system... this
    * will simply check if there is a solution in the solutions pool; and if one exists, will return everything about
    * the solution, and if not, will then start looking google for a solution... it's going to query google regarding
    * the error message...
    *
    * @param Throwable $throwable
    * @return bool
    */
    public function canSolve(Throwable $throwable): bool
    {
        // capture the throwable instance against the ExceptionSolutionProvider instance...
        $this->throwable = $throwable;

        // capture what the file is that the error is coming from.
        $this->solution = preg_replace('/\/(.*)\/|(\.php)/', '', $throwable->getFile());

        // if the internet searching is enabled... and the above solution cannot be found, then we are going to resort
        // to trying to find something from the internet... then it will go through the motions of getting internet
        // search results.
        if ($this->internet_searching_enabled && ! isset($this->solutions[$this->solution]))
            $this->getInternetSolutions($throwable->getMessage());

        // return whether or not we can solve the issue, which is basically if the solutions exist against the thing
        // we are trying to solve, OR if we have any internet solutions regarding the issue at hand, but this will
        // only want to happen, if there is no solution set, so that we can run off and collect some solutions, which
        // will be a concept of optimisations and removing unnecessary methods where possible; each solution will be
        // pre-loaded with some links regarding the error at hand (if it is a known issue).
        return isset($this->solutions[$this->solution]) ||
               !! $this->internet_solutions;
    }

    /**
    * This method will iterate over all the possible solutions, and return them to the user... all possible solutions
    * that can be given to a user will be served to the developer.
    *
    * @param Throwable $throwable
    * @return array
    */
    public function getSolutions(Throwable $throwable): array
    {
        $solution = $this->solutions[$this->solution];
        return [new $solution($this)];
    }

    /**
    * This is a global getter for the ExceptionSolutionProvider; and this will allow you to return any part of this
    * particular object instance with ease, just by utilising the stringed alias variation of the text property, this
    * is going to return any part of this... this is just a global accessor to the ESP
    *
    * @param string $alias
    * @return mixed
    */
    public function get(string $alias)
    {
        return $this->$alias;
    }

    /**
    * This method is only going to run if the existing solution hasn't been found in the stack of solutions that has
    * been pre-defined against this class... this method is then going to search the internet for a solution, and the
    * search will be entirely based off of the message that will have been passed from the throwable exception upon
    * checking whether or not the user can solve... by default this method is going to automatically set the solution
    * to the internet search, so that upon the collection of some internet searches, we can then provide some
    * response based on what comes back
    *
    * @param string $message
    * @return void
    */
    private function getInternetSolutions(string $message): void
    {
        preg_match_all('/(?<=<a href=")(.*?)(?=")/', $this->executeInternetSearch($message), $matches);

        $this->internet_solutions = collect(array_filter(array_merge(...$matches), function ($value) {
            if (!! $this->internet_searching_pages) {
                foreach ($this->internet_searching_pages as $internet_searching_page) {
                    return str_contains($value, $internet_searching_page);
                }
            } return $value;
        }))->take($this->internet_searching_results);

        if ($this->internet_solutions->isNotEmpty())
            $this->solution = 'Internet';
    }

    /**
    * This method is strictly for executing and returning the contents of an internet search query, this supports
    * google.com search queries at the current moment in time, intending on adding support from internet explorer
    * firefox, safari, opera and more.
    *
    * @param string $message
    * @return string
    */
    private function executeInternetSearch(string $message): string
    {
        return (string) file_get_contents(
            $this->internet_searching_engine_query_strings[
                $this->internet_searching_engine
            ] . urlencode($message)
        );
    }

    /**
    * This method is for binding/registering some more resolutions on the fly, if we are going to be needing them at
    * any time... otherwise, if we are going to be wanting any more exceptions "dynamically" driven away frmo this
    * particular class, then we can execute the register solution inside of the AppServiceProvider
    *
    * @param string $abstract
    * @param string|null $concrete
    * @return self
    */
    public function registerSolution(string $abstract, ?string $concrete = null): self
    {
        if ($concrete === null)
            $concrete = $abstract;

        $this->solutions[$concrete] = $abstract;
        return $this;
    }
}