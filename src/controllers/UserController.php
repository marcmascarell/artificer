<?php

namespace Mascame\Artificer;

use Auth;
use View;
use Input;
use Session;
use Redirect;
use Validator;
use Carbon\Carbon;
use Mascame\Artificer\Options\AdminOption;

class UserController extends BaseController
{
    public $tries_key = 'artificer.user.login.tries';
    public $ban_key = 'artificer.user.login.banned';

    /**
     * Unban user.
     */
    private function unban()
    {
        Session::forget($this->ban_key);
    }

    /**
     * @return bool
     */
    private function isBanned()
    {
        if (Session::has($this->ban_key)) {
            $ban = Carbon::parse(Session::get($this->ban_key));

            if (! $ban->isPast()) {
                return true;
            }
        }

        $this->unban();

        return false;
    }

    /**
     * Ban user.
     */
    private function ban()
    {
        Session::set($this->ban_key, Carbon::now()->addMinutes(AdminOption::get('auth.ban_time')));
    }

    private function addAttempt()
    {
        $tries = Session::get($this->tries_key);

        if (! $tries) {
            $tries = 1;
        } else {
            $tries++;
        }

        Session::set($this->tries_key, $tries);

        if ($tries >= AdminOption::get('auth.max_login_attempts')) {
            $this->ban();
            Session::forget($this->tries_key);
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return Redirect::route('admin.home');
        }

        return View::make($this->getView('pages.login'));
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function login()
    {
        if ($this->isBanned()) {
            return Redirect::route('admin.showlogin')->withErrors(['You are banned for too many login attempts']);
        }

        $rules = [
            'username' => 'required|email',
            'password' => 'required|min:3',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return $this->onFailValidation($validator);
        }

        /*
         * Todo: add also to banning in case of fail auth attempt
         */
        if ($this->isValidUser($this->getUser())) {
            return Redirect::route('admin.home');
        }

        return Redirect::route('admin.login')
            ->withInput(Input::except('password'))->withErrors(['The user credentials are not correct or does not have access']);
    }

    protected function onFailValidation($validator)
    {
        $this->addAttempt();

        return Redirect::route('admin.login')
            ->withErrors($validator)
            ->withInput();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    protected function getUser()
    {
        return \User::where('email', '=', Input::get('username'))->first();
    }

    /**
     * @param $user
     * @return bool
     */
    protected function attemptLogin($user)
    {
        $role_colum = AdminOption::get('auth.role_column');
        if (in_array($user->$role_colum, AdminOption::get('auth.roles'))) {
            $userdata = [
                'email'    => Input::get('username'),
                'password' => Input::get('password'),
            ];

            if (Auth::attempt($userdata)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|null $user
     * @return bool
     */
    protected function isValidUser($user)
    {
        if ($user) {
            if ($this->attemptLogin($user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();

        return Redirect::route('admin.showlogin');
    }
}
