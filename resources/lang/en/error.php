<?php

return [
    '403' => <<<EOT
        Unfortunately, my good sir, you do not have the permissions to view this, you may want to check out the store,
        get yourself the access to this particular portion of the system, if you don't really care, then feel free to 
        trape back to where you came from, foo.
    EOT,
    '404' => <<<EOT
        My apologies, I know you're trying to be productive, but please try and find the right place you're looking for, 
        huh? you ain't gon be productive on this beautiful looking page.
    EOT,
    '429' => 'You have tried logging in too many times.',
    '503' => <<<EOT
        We are currently doing some maintenance on this application... we shouldn't be too much longer but if you wanna 
        stick around then you are most welcome to refresh a few times hoping that we're coming back to soon enough.
    EOT,
    'oops' => <<<EOT
        Something went wrong there, There may potentially be something wrong with this particular page and or feature... 
        please help the development team by escalating this issue so that we are aware of where and when this is 
        happening. Thanks.
    EOT,
];