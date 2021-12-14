<?php

namespace App\Models\Traits\Getters;

use Parsedown;

trait GetterMarkdown
{
    /**
    * This method is for acquiring content back from the database in it's html format, this will be bringing back by
    * default in safemode, you can override the safe mode by passing in false. this method works in the opposite way
    * of the setParsedContent method in the SetterMarkdown trait.
    *
    * @param mixed $value
    * @param bool $safe_mode
    * @return string
    */
    public function getParsedContent(mixed $value, bool $safe_mode = true): string
    {
        return ((new Parsedown())->setSafeMode($safe_mode))->parse($value);
    }
}