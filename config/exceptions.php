<?php

return [

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Exceptions and Solutions
    |-------------------------------------------------------------------------------------------------------------------
    |
    | Here you specify some core options regarding the solution reporting whenever exceptions have been hit... this is
    | going to be setting some default options, which provider to use, google/firefox/edge etc... whether or not this
    | is enabled and more.
    |
    */

    'internet_searching_enabled' => env('internet_searching_enabled', true),

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Internet Searching: Engines
    |-------------------------------------------------------------------------------------------------------------------
    |
    | Defining which search engines to use, the default here is going to be the leading searching provider, google...
    | the supported engines that are currently supported are:
    | - google
    | - yahoo
    | - ask-jeeves
    |
    */

    'internet_searching_engine'  => env('internet_searching_engine', 'google'),

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Internet Searching: Returned Pages
    |-------------------------------------------------------------------------------------------------------------------
    |
    | how many results are going to be wanting to return when the system finds an error; the number of results ranges
    | from 0 - 50... the less links you use, the more accurate the result sets are going to be against the error at
    | hand.
    |
    */

    'internet_searching_results' => env('internet_searching_results', 5),

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Internet Searching: Returned Pages
    |-------------------------------------------------------------------------------------------------------------------
    |
    | what pages are we going to be allowing to come back into the ServiceProvider that will display solutions from...
    | if you have a designated set of pages that you would like to return, then the order in which will be returned
    | as a priority sense, is the one's that are entered first in this list.
    |
    */

    'internet_searching_pages' => [
        'stackoverflow'
    ]
];