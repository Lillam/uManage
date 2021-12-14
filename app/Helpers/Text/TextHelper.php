<?php

namespace App\Helpers\Text;

class TextHelper
{
    /**
    * TextHelper constructor.
    */
    public function __construct()
    {

    }

    /**
    * This method is entirely for strippign out tags from from the uploaded values, this will replace all tags for the
    * special entity variation of it... this is going to allow for safer inserted.
    *
    * @param $string
    * @return string
    */
    public static function safeTags($string): string
    {
        return str_replace(['<', '>'], ['&lt;', '&gt;'], $string);
    }

    /**
    * This method will be used in conjunction with - the Converter package, which will take care of removing all
    * styles, ids, scripts, etc to make sure that the content we're passing into the database is completely secure...
    * this may want altering over time as new things are introduced, however; this is a security method to strip
    * attributes from a string.
    *
    * @param $string
    * @return string
    */
    public static function stripAttributes($string): string
    {
        $string = preg_replace(
            '#\s(id|class|style|onclick|dir|role)="[^"]+"#',
            '',
            $string
        );

        $string = str_replace(
            ['<span>', '</span>'],
            '',
            $string
        );

        return $string;
    }

    /**
    * This method will strip out spaces... and replace it for hyphens (-) after doing that it will turn the entire
    * string into a lowercase variation, so we can do a direction match from the url against what is coming out
    * on the backend (alias match will be better than name/title matching).
    *
    * @param string $string
    * @return string
    */
    public static function slugify(string $string): string
    {
        return strtolower(str_replace(' ', '-', $string));
    }
}