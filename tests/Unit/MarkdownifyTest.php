<?php

namespace Tests\Unit;

use App\Models\Traits\Getters\GetterMarkdown;
use App\Models\Traits\Setters\SetterMarkdown;
use PHPUnit\Framework\TestCase;

class Markdownify
{
    use GetterMarkdown;
    use SetterMarkdown;
}

class MarkdownifyTest extends TestCase
{
    public function testMarkdownify()
    {
        $markdown = (new Markdownify())->setParsedContent("random ", true);
        dd($markdown);
    }
}
