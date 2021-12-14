<?php

namespace App\Printers\User;

use App\Models\User\User;

class UserPrinter
{
    /**
    * Method for acquiring a passed user, in badge form. if there is ever a place where we are going to be utilising
    * a user display for the frontend then we are going to want to show the user, this will return their initials,
    * unless it has been requested that their full name is present, in which the full name will be inserted alongside
    * the badge.
    *
    * @param User|null $user
    * @param false $with_name
    * @param null $color
    * @return string
    */
    public static function userBadge(User $user = null, $with_name = false, $color = null): string
    {
        $style = $color !== null ? "border-color: $color;" : '';

        return $user === null
            ? '<span><i class="fa fa-user"></i></span>'
            : "<span class='badge-image'><img style='$style' src='{$user->getProfileImage()}' /></span>";
    }

    /**
    * Method for acquiring a list of user profiles, in badge form, if there is ever a place where we're going to be
    * wanting to display a list of users, alongside one another, and in the form of badges with their user name, then
    * this method is going to be the hub method for getting an array of users passed in a badge list format.
    *
    * @param $users
    * @param false $with_name
    * @param null $color
    *
    * @return string
    */
    public static function userBadges($users, $with_name = false, $color = null): string
    {
        if ($users->isEmpty())
            return '';

        $style = $color !== null ? "border-color: $color;" : '';
        $html = '';

        foreach ($users as $user) {
            $html .= "<span class='badge-image'>";
                $html .= "<img style='$style' src='{$user->getProfileImage()}' />";
                $html .= $with_name !== false ? $user->getFullName() : '';
            $html .= "</span>";
        }

        return $html;
    }

    /**
    * Method for turning passed user into a linked format, this will be returning the users full name (if they have one
    * otherwise this is simply going to return their first name, if they have one).
    *
    * @param User $user
    * @return string
    */
    public static function linkedUser(User $user): string
    {
        if (! $user instanceof User) {
            return '';
        }

        $html = '<a href="' . action('User\UserController@_viewUserGet', $user->id) . '">';
            $html .= $user->getFullName();
        $html .= '</a>';

        return $html;
    }
}