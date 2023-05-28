<?php

namespace App\Models\Traits\Setters;

use Markdownify\Converter;
use App\Helpers\Text\TextHelper;

trait SetterMarkdown
{
    /**
    * This method is for setting the content into a parsed format, this will turn all html into a markdown string which
    * will be a safer method of putting the content into the database. This sets it into the database in a html-less
    * format.
    *
    * @param mixed $value
    * @param bool $safeMode
    * @return string
    */
    public function setParsedContent(mixed $value, bool $safeMode = true): string
    {
        $converter = new Converter();
        $converter->setKeepHTML(! $safeMode);
        return $converter->parseString(TextHelper::stripAttributes($value));
    }
}