<?php

namespace App\Http\Controllers\Web\User;

use App\Models\User\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Web\Controller;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('throttle:3,1')->only('_viewUserLoginPost');
    }

    /**
     * when the user has signed in they are going to be needing a particular page that they are greeted with, this will
     * be the route to maintaining teh user's home screen.
     *
     * @return Factory|View
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function _viewUserDashboardGet(): Factory|View
    {
        $this->vs->set('title', "Dashboard - {$this->vs->get('user')->getFullName()}")
                 ->set('ref', 'dashboard')
                 ->set('currentPage', 'page.dashboard')
                 ->set('hasTitle', false)
                 ->set('hasSidebar', false);

        $this->vs->get('user')->can('Policy@canI');

        return view('user.view_user_dashboard');
    }

    /**
    * This method is entirely for rendering the frontend visuals for the user's login form. this particular page will be
    * void of the header and footer of the system because the user will not necessarily need to view these particular
    * elements until being signed in.
    *
    * @return Factory|View|RedirectResponse
     */
    public function _viewUserLoginGet(): Factory|View|RedirectResponse
    {
        if (Auth::user()) {
            return redirect()->route('user.dashboard');
        }

        $this->vs->set('title', 'Login')
                 ->set('currentPage', 'login')
                 ->set('hasSidebar', false)
                 ->set('hasHeader', false)
                 ->set('hasTitle', false)
                 ->set('hasFooter', false);

        return view('user.view_user_login');
    }

    /**
    * This method handles the posting of a user attempting to log into the system. all this method takes is an
    * email and a password and if they match the user's entry then the user will be redirected their designated
    * page, otherwise they will be redirected back to the sign-in page with the corresponding errors...
    *
    * @param Request $request
    * @return RedirectResponse|Redirector
    * @throws ValidationException
    */
    public function _viewUserLoginPost(Request $request): RedirectResponse|Redirector
    {
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt([
            'email'    => $request->input('email'),
            'password' => $request->input('password')
        ])) {
            return redirect()->route('user.dashboard');
        }

        return back()->withInput([
            'error' => 'Wrong Login Details',
            'email' => $request->input('email')
        ]);
    }

    /**
    * this method is for returning all the users in the system to a page... this will be the default method of being
    * able to look at what users are currently in the system, currently with no restriction at all...
    *
    * @return Factory|View
    */
    public function _viewUsersGet(): Factory|View
    {
        $users = User::all();

        $this->vs->set('title', '- users');

        return view('user.view_users', compact(
            'users'
        ));
    }

    /**
    * this method is simply returning a single user page...
    *
    * @param int $userId
    * @return Factory|RedirectResponse|View
    */
    public function _viewUserGet(int $userId): Factory|RedirectResponse|View
    {
        // find the user that the requested user is trying to view, we are going to be wanting to accumulate a variety
        // of this particular user's data from the get go so that we can print a dashboard like view with a variety
        // of different data.
        $user = User::with([
            'projects',
            'projects.userContributors',
            'projects.userContributors.user',
            'tasks',
            'tasks.taskStatus',
            'tasks.taskPriority',
            'tasks.taskIssueType'
        ])->where('id', '=', $userId)->first();

        // if the user we have searched for somehow doesn't happen to be an instance of user... then we are going
        // to want to redirect the user back to the user/1 page... utilising it's method, this in theory
        // should only ever happen if the user has opted to free search this in the url...
        if (! $user instanceof User || $this->vs->get('user')->cannot('UserPolicy@viewUser', $user)) {
            return redirect()->action('User\UserController@_viewUsersGet');
        }

        // when we're looking at the user in question then we are going to be gathering the user's people that are
        // attached to some of their projects, this will give an i   dea for the user on the amount of people they are
        // going to be working with on a variety of different projects. this can get quite large, as we are going to be
        // looking at a user project collection, so if the same user is connected to a variety of other projects, then
        // we are only wanted to get unique entries.
        $usersIWorkWith = collect();

        $user->projects->map(function ($project) use (&$usersIWorkWith) {
            $project->userContributors->map(function ($user) use (&$usersIWorkWith) {
                if ($user->user->id !== $this->vs->get('user')->id) {
                    $usersIWorkWith[$user->user->id] = $user->user;
                }
            });

            return $project;
        });

        $this->vs->set('title', " - {$user->getFullName()}")
                 ->set('ref', 'account')
                 ->set('currentPage', 'page.user');

        return view('user.view_user', compact(
            'user',
            'usersIWorkWith'
        ));
    }

    /**
    * @param Request $request
    * @return Factory|View
    */
    public function _viewUserEditGet(Request $request): Factory|View
    {
        // todo this method needs fleshing out for everything that this method is going to need to acquire...
        //      this method also needs the view service setting the title of the page...
        return view('user.view_user_edit');
    }

    public function _viewUserEditPost(Request $request)
    {
        // todo this method needs fleshing out and placing all the logic in place that i'm going to be needing for the
        //      user editing process...
    }

    /**
    * This method is simply just for signing the user out, the user needs a method for being able to exit the system,
    * the logout method will direct the user back to the login page, as that is the only page a user should be able to
    * access whilst not logged in, or even forgot password.
    *
    * @return RedirectResponse|Redirector
    */
    public function _userLogout(): RedirectResponse|Redirector
    {
        Auth::logout();

        return redirect()->route('user.logout');
    }
}
