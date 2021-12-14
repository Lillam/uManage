<?php

namespace App\Http\Controllers\User;

use Illuminate\View\View;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
    * UserController constructor.
    */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('throttle:3,1')->only('_viewUserLoginPost');
    }

    /**
    * when the user has signed in they are going to be needing a particular page that they are greeted with, this will
    * be the route to maintaining teh user's home screen.
    *
    * @param Request $request
    * @return Factory|View
    */
    public function _viewUserDashboardGet(Request $request): Factory|View
    {
//        dd(
//            Project::select('*')->whereRaw(request('query') ?? '1=1')->get()
//        );
        $this->vs->set('title', "Dashboard - {$this->vs->get('user')->getFullName()}")
                 ->set('ref', 'dashboard')
                 ->set('current_page', 'page.dashboard');

        $this->vs->get('user')->can('Policy@canI');

        return view('user.view_user_dashboard');
    }

    /**
    * This method is entirely for rendering the frontend visuals for the user's login form. this particular page will be
    * void of the header and footer of the system because the user will not necessarily need to view these particular
    * elements until being signed in.
    *
    * @param Request $request
    * @return Factory|View
    */
    public function _viewUserLoginGet(Request $request): Factory|View
    {
        if ($this->vs->get('user') instanceof User)
            return redirect()->action('User\UserController@_viewUserDashboardGet');

        $this->vs->set('title', 'Login')
                 ->set('current_page', 'login')
                 ->set('has_sidebar', false)
                 ->set('has_header', false)
                 ->set('has_footer', false);

        return view('user.view_user_login');
    }

    /**
    * This method handles the posting of a user attempting to log into the system. all this method takes is a
    * email and a password and if they match the user's entry then the user will be redirected their designated
    * page, otherwise they will be redirected back to the sign in page with the corresponding errors...
    *
    * @param Request $request
    * @return RedirectResponse|Redirector
    * @throws ValidationException
    */
    public function _viewUserLoginPost(Request $request): RedirectResponse|Redirector
    {
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required|min:3'
        ]);

        if (Auth::attempt([
            'email'    => $request->input('email'),
            'password' => $request->input('password')
        ])) return redirect()->action('User\UserController@_viewUserDashboardGet');

        return back()->withInput([
            'error' => 'Wrong Login Details',
            'email' => $request->input('email')
        ]);
    }

    /**
    * this method is for returning all the users in the system to a page... this will be the default method of being
    * able to look at what users are currently in the system, currently with no restriction at all...
    *
    * @param Request $request
    * @return Factory|View
    */
    public function _viewUsersGet(Request $request): Factory|View
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
    * @param Request $request
    * @param $user_id
    * @return Factory|RedirectResponse|View
    */
    public function _viewUserGet(Request $request, int $user_id): Factory|RedirectResponse|View
    {
        // find the user that the requested user is trying to view, we are going to be wanting to accumulate a variety
        // of this particular user's data from the get go so that we can print a dashboard like view with a variety
        // of different data.
        $user = User::with([
            'projects',
            'projects.user_contributors',
            'projects.user_contributors.user',
            'tasks',
            'tasks.task_status',
            'tasks.task_priority',
            'tasks.task_issue_type'
        ])->where('id', '=', $user_id)->first();

        // if the user we have searched for somehow doesn't happen to be an instance of user... then we are going
        // to want to redirect the user back to the user/1 page... utilising it's method, this in theory
        // should only ever happen if the user has opted to free search this in the url...
        if (! $user instanceof User || $this->vs->get('user')->cannot('UserPolicy@viewUser', $user))
            return redirect()->action('User\UserController@_viewUsersGet');

        // when we're looking at the user in question then we are going to be gathering the users people that are
        // attached to some of their projects, this will give an i   dea for the user on the amount of people they are
        // going to be working with on a variety of different projects. this can get quite large, as we are going to be
        // looking at a user project collection, so if the same user is connected to a variety of other projects, then
        // we are only wanted to get unique entries.
        $users_i_work_with = collect();
        $user->projects->map(function ($project) use (&$users_i_work_with) {
            $project->user_contributors->map(function ($user) use (&$users_i_work_with) {
                if ($user->user->id !== $this->vs->get('user')->id)
                    $users_i_work_with[$user->user->id] = $user->user;
            }); return $project;
        });

        $this->vs->set('title', " - {$user->getFullName()}")
                  ->set('ref', 'account')
                  ->set('current_page', 'page.user');

        return view('user.view_user', compact(
            'user',
            'users_i_work_with'
        ));
    }

    /**
    * @param Request $request
    * @return Factory|View
    */
    public function _viewUserEditGet(Request $request): Factory|View
    {
        // todo this method needs fleshing out for everything that this method is going to need to acquire...
        // todo this method also needs the view service setting the title of the page...
        return view('user.view_user_edit');
    }

    public function _viewUserEditPost(Request $request)
    {
        // todo this method needs fleshing out and placing all the logic in place that i'm going to be needing for the
        //  user editing process...
    }

    /**
    * This method is simply just for signing the user out, the user needs a method for being able to exit the system,
    * the logout method will direct the user back to the login page, as that is the only page a user should be able to
    * access whilst not logged in, or even forgot password.
    *
    * @param Request $request
    * @return RedirectResponse|Redirector
    */
    public function _userLogout(Request $request): RedirectResponse|Redirector
    {
        Auth::logout();
        return redirect()->action([self::class, '_viewUserLoginGet']);
    }
}