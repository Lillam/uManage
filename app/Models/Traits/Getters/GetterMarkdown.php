<?php

namespace App\Models\Traits\Getters;

use Parsedown;

trait GetterMarkdown
{
    /**
    * This method is for acquiring content back from the database in it's html format, this will be bringing back by
    * default in safe mode, you can override the safe mode by passing in false. this method works in the opposite way
    * of the setParsedContent method in the SetterMarkdown trait.
    *
    * @param mixed $value
    * @param bool $safeMode
    * @return string
    */
    public function getParsedContent(mixed $value, bool $safeMode = true): string
    {
        return ((new Parsedown())->setSafeMode($safeMode))->parse($value);
    }
}