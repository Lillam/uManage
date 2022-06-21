<?php

use Illuminate\Support\Facades\Broadcast;

/*
|-----------------------------------------------------------------------------------------------------------------------
| Broadcast Channels
|-----------------------------------------------------------------------------------------------------------------------
|
| Here you may register all the event broadcasting channels that your application supports. The given channel
| authorisation callbacks are used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
