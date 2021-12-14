<?php

namespace App\Services\View;

use App\Models\User\User;

class ViewService
{
    /**
    * @var string
    */
    private string $title = '';

    /**
    * @var string
    */
    private string $current_page = '';

    /**
    * @var string
    */
    private string $application_theme = 'dark-theme';

    /**
    * @var bool
    */
    private bool $has_header = true;

    /**
    * @var bool
    */
    private bool $has_footer = true;

    /**
    * @var bool
    */
    private bool $has_sidebar = true;

    /**
    * @var User|null
    */
    private User|null $user = null;

    /**
    * @var User|null
    */
    private User|null $viewing_user = null;

    /**
    * @return object
    */
    public function all(): object
    {
        return (object) [
            'title'              => $this->getTitle(),
            'current_page'       => $this->getCurrentPage(),
            'is_page'            => fn (string $page): string => $page === $this->getCurrentPage() ? 'active' : '',
            'has_header'         => $this->getHasHeader(),
            'has_footer'         => $this->getHasFooter(),
            'has_sidebar'        => $this->getHasSidebar(),
            'application_theme'  => $this->getApplicationTheme(),

            // deciding which user is the currently logged in user, and then also having a setting which will let
            // the system know who the user is currently viewing; rather than overwriting the user that is in
            // place.
            'user'               => $this->getUser(),
            'viewing_user'       => $this->getViewingUser(),

            // some random view service helper methods for being able to return some assets to the user.
            'css'                => fn (string $asset): string => $this->getCssAsset($asset),
            'js'                 => fn (string $asset): string => $this->getJsAsset($asset)
        ];
    }

    /**
    * @param string $title
    * @return $this
    */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
    * @return string
    */
    private function getTitle(): string
    {
        return env('APP_NAME') . " $this->title";
    }

    /**
    * @param string $current_page
    * @return $this
    */
    public function setCurrentPage(string $current_page): self
    {
        $this->current_page = $current_page;
        return $this;
    }

    /**
    * @return string
    */
    private function getCurrentPage(): string
    {
        return $this->current_page ?? '';
    }

    /**
    * @param bool $has_header | A deciding factor as to whether or not the header is visible on the frontend, in the
    *                         | application the header tag is wrapped in an has_header wrapper; and if this is false
    *                         | the header is not visible.
    * @return $this
    */
    public function setHasHeader(bool $has_header): self
    {
        $this->has_header = $has_header;
        return $this;
    }

    /**
    * @return bool
    */
    private function getHasHeader(): bool
    {
        return $this->has_header;
    }

    /**
    * @param bool $has_footer | A deciding factor as to whether or not the footer is visible on the frontend, in the
    *                         | application the footer tag is wrapped in an has_footer wrapper; and if this is false,
    *                         | the footer is not visible.
    * @return $this
    */
    public function setHasFooter(bool $has_footer): self
    {
        $this->has_footer = $has_footer;
        return $this;
    }

    /**
    * @return bool
    */
    private function getHasFooter(): bool
    {
        return $this->has_footer;
    }

    /**
    * @param bool $has_sidebar
    * @return $this
    */
    public function setHasSidebar(bool $has_sidebar): self
    {
        $this->has_sidebar = $has_sidebar;
        return $this;
    }

    /**
    * @return bool
    */
    private function getHasSidebar(): bool
    {
        return $this->has_sidebar;
    }

    /**
    * @param string $application_theme
    * @return $this
    */
    public function setApplicationTheme(string $application_theme): self
    {
        $this->application_theme = $application_theme;
        return $this;
    }

    /**
    * @return string
    */
    private function getApplicationTheme(): string
    {
        return $this->application_theme ?? '';
    }

    /**
    * @param User $user
    * @return $this
    */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
    * @return null|User
    */
    private function getUser(): null|User
    {
        return $this->user;
    }

    /**
    * @param User $viewing_user
    * @return $this
    */
    public function setViewingUser(User $viewing_user): self
    {
        $this->viewing_user = $viewing_user;
        return $this;
    }

    /**
    * @return User|null
    */
    private function getViewingUser(): null|User
    {
        return $this->user;
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Global Getter Setters
    |
    | From here on is a variety of global getters and setters, that are more defined as helper methods for being able to
    | Quickly access some variable in the view service, without actually having to type out the full getter setter
    | method.
    |---------------------------------------------------------------------------------------------------------------- */

    /**
    * Method for acquiring any of the items inside this particular class. ->get('title') will in fact, return whatever
    * value that is currently set against this object.
    *
    * @param $key
    * @return mixed
    */
    public function get(string $key): mixed
    {
        return $this->$key;
    }

    /**
    * Method for unsetting any variable that currently sits inside this object, inside a specific instance. if the
    * specific areas of the system do not require the use of a variable inside here, we can unset it to save some
    * memory.
    *
    * @param $key
    * @return $this
    */
    public function remove($key): self
    {
        unset($this->$key);
        return $this;
    }

    /**
    * This is a method for setting variables to the object. (There should only ever be one object for ViewService
    * in which we are going to be utilising this particular method for setting the variables necessary).
    *
    * @param string $key
    * @param mixed $value
    * @return $this
    */
    public function set(string $key, mixed $value): self
    {
        $this->$key = $value;
        return $this;
    }

    /**
    * A view service helper method which will return an asset from the public directory. and will be returning a html
    * string set so that the html can render the stylesheet to the frontend.
    *
    * @param string $asset
    * @return string
    */
    private function getCssAsset(string $asset): string
    {
        $file = asset(str_replace('.css', '', $asset) . '.css');
        return "<link href='$file' rel='stylesheet' type='text/css' />";
    }

    /**
    * A view service helper method which will return an asset from the public directory, and will be returning a html
    * string set so that the html can render the javascript to the frontend.
    *
    * @param string $asset
    * @return string
    */
    private function getJsAsset(string $asset): string
    {
        $file = asset(str_replace('.js', '', $asset) . '.js');
        return "<script src='$file'></script>";
    }
}