<?php

namespace App\Exceptions\Solutions;

use ReflectionClass;
use ReflectionException;
use Illuminate\Support\Collection;
use Facade\IgnitionContracts\Solution;
use App\Providers\ExceptionSolutionProvider;

class RelationNotFoundExceptionSolution implements Solution
{
    private $esp;

    /**
    * RelationNotFoundExceptionSolution constructor.
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
        return 'The target relationship does not exist';
    }

    /**
    * @return string
    * @throws ReflectionException
    */
    public function getSolutionDescription(): string
    {
        return '_You are trying to return an eloquent model with a relationship that has not been defined, you might ' .
            'have spelt the relationship wrong. If you are struggling to understand what the problem might be, take a ' .
            'read through the links below.' . ' ' .
            implode(", ", $this->getRelationshipsFromModel()->pluck('name')->toArray()) .
            ' are the only defined relationships against this model_';
    }

    /**
    * @return array[]
    */
    public function getDocumentationLinks(): array
    {
        return [
            'Relationships' => 'https://laravel.com/docs/8.x/eloquent#introduction',
            'Models'        => 'https://laravel.com/docs/8.x/eloquent-serialization#serializing-models-and-collections',
            'Eloquent'      => 'https://laravel.com/docs/8.x/eloquent#introduction'
        ];
    }

    /**
    * This is a method that will acquire all of the methods that are sitting against the model that had a relationship
    * failed against, this method is going to spawn a reflection class of the user model, and then bring back all of
    * the methods that reside against a model... and upon this happening, is then going to start trimming out all
    * of the values that aren't a relationship entity, and then return this in the form of a collection.
    *
    * @return Collection
    * @throws ReflectionException
    */
    private function getRelationshipsFromModel(): Collection
    {
        return collect(array_filter(
            array_map(function ($value) {
                return (object) [
                    'name'        => "**{$value->getName()}**",
                    'return_type' => (string) $value->getReturnType()
                ];
            }, (new ReflectionClass($this->esp->get('throwable')->model))->getMethods()
            ), function ($value) {
                return strpos($value->return_type, 'Has') !== false ||
                       strpos($value->return_type, 'Belongs') !== false;
            }
        ));
    }
}