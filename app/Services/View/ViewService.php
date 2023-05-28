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
    private string $currentPage = '';

    /**
    * @var string
    */
    private string $applicationTheme = 'dark-theme';

    /**
    * @var bool
    */
    private bool $hasHeader = true;

    /**
    * @var bool
    */
    private bool $hasFooter = true;

    /**
     * @var bool
     */
    private bool $hasTitle = true;

    /**
    * @var bool
    */
    private bool $hasSidebar = true;

    /**
    * @var User|null
    */
    private User|null $user = null;

    /**
    * @var User|null
    */
    private User|null $viewingUser = null;

    /**
    * @return object
    */
    public function all(): object
    {
        return (object) [
            'title'              => $this->getTitle(),
            'currentPage'        => $this->getCurrentPage(),
            'isPage'             => fn (string $page): string => mb_strpos(
                $this->getCurrentPage(),
                $page
            ) !== false ? 'active' : '',
            'hasHeader'          => $this->getHasHeader(),
            'hasFooter'          => $this->getHasFooter(),
            'hasSidebar'         => $this->getHasSidebar(),
            'hasTitle'           => $this->getHasTitle(),
            'applicationTheme'   => $this->getApplicationTheme(),

            // deciding which user is the currently authenticated user, and then also having a setting which will let
            // the system know who the user is currently viewing; rather than overwriting the user that is in
            // place.
            'user'               => $this->getUser(),
            'viewingUser'        => $this->getViewingUser(),

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
    * @param string $currentPage
    * @return $this
    */
    public function setCurrentPage(string $currentPage): self
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
    * @return string
    */
    private function getCurrentPage(): string
    {
        return $this->currentPage ?? '';
    }

    /**
    * @param bool $hasHeader A deciding factor whether the header is visible on the frontend or not, in the
    *                        application the header tag is wrapped in a has_header wrapper; and if this is false
    *                        the header is not visible.
    * @return $this
    */
    public function setHasHeader(bool $hasHeader): self
    {
        $this->hasHeader = $hasHeader;

        return $this;
    }

    /**
    * @return bool
    */
    private function getHasHeader(): bool
    {
        return $this->hasHeader;
    }

    /**
    * @param bool $hasFooter A deciding factor whether the footer is visible on the frontend or not, in the
    *                        application the footer tag is wrapped in a has_footer wrapper; and if this is false,
    *                        the footer is not visible.
    * @return $this
    */
    public function setHasFooter(bool $hasFooter): self
    {
        $this->hasFooter = $hasFooter;

        return $this;
    }

    /**
    * @return bool
    */
    private function getHasFooter(): bool
    {
        return $this->hasFooter;
    }

    /**
     * @param bool $hasTitle
     * @return $this
     */
    public function setHasTitle(bool $hasTitle): self
    {
        $this->hasTitle = $hasTitle;

        return $this;
    }

    public function getHasTitle(): bool
    {
        return $this->hasTitle;
    }

    /**
    * @param bool $hasSidebar
    * @return $this
    */
    public function setHasSidebar(bool $hasSidebar): self
    {
        $this->hasSidebar = $hasSidebar;

        return $this;
    }

    /**
    * @return bool
    */
    private function getHasSidebar(): bool
    {
        return $this->hasSidebar;
    }

    /**
    * @param string $applicationTheme
    * @return $this
    */
    public function setApplicationTheme(string $applicationTheme): self
    {
        $this->applicationTheme = $applicationTheme;

        return $this;
    }

    /**
    * @return string
    */
    private function getApplicationTheme(): string
    {
        return $this->applicationTheme ?? '';
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
    * @param User $viewingUser
    * @return $this
    */
    public function setViewingUser(User $viewingUser): self
    {
        $this->viewingUser = $viewingUser;

        return $this;
    }

    /**
    * @return User|null
    */
    private function getViewingUser(): null|User
    {
        return $this->viewingUser;
    }

    /*
    |-------------------------------------------------------------------------------------------------------------------
    | Global Getter Setters
    |-------------------------------------------------------------------------------------------------------------------
    |
    | From here on is a variety of global getters and setters, that are more defined as helper methods for being able to
    | Quickly access some variable in the view service, without actually having to type out the full getter setter
    | method.
    |
    */

    /**
    * Method for acquiring any of the items inside this particular class. ->get('title') will in fact, return whatever
    * value that is currently set against this object.
    *
    * @param string $key
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
    * @param string $key
    * @return $this
    */
    public function remove(string $key): self
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