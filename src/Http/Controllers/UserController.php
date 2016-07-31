<?php namespace Mascame\Artificer\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Auth\EloquentUserProvider;
use Input;
use Mascame\Artificer\Options\AdminOption;
use Redirect;
use Session;
use Validator;
use View;

class UserController extends BaseController
{

    public $tries_key = 'artificer.user.login.tries';
    public $ban_key = 'artificer.user.login.banned';
    public $authProvider;

    public function __construct() {
        parent::__construct();

        $this->authProvider = new EloquentUserProvider(app('hash'), 'User');
    }

    /**
     * Unban user
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
     * Ban user
     */
    private function ban()
    {
        Session::set($this->ban_key, Carbon::now()->addMinutes(AdminOption::get('auth.ban_time')));
    }

    /**
     *
     */
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
        if (Auth::check()) return Redirect::route('admin.home');

        return View::make($this->getView('pages.login'));
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function login()
    {
        if ($this->isBanned()) {
            return Redirect::route('admin.showlogin')->withErrors(array("You are banned for too many login attempts"));
        }

        $rules = array(
            'username' => 'required|email',
            'password' => 'required|min:3'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return $this->onFailValidation($validator);
        }

        /*
         * Todo: add also to banning in case of fail auth attempt
         */
        if ($this->isValidUser($this->getUser())) {
            return $this->successLoginRedirect();
        }

        return $this->failedLoginRedirect();
    }

    protected function successLoginRedirect() {
        return Redirect::route('admin.home');
    }

    protected function failedLoginRedirect() {
        return Redirect::route('admin.login')
            ->withInput(Input::except('password'))->withErrors(array('The user credentials are not correct or does not have access'));
    }

    protected static function attempt($attemptClosure, $credentials) {
        return is_callable($attemptClosure) ? $attemptClosure($credentials) : false;
    }

    protected static function getClosureAttempt() {
        return AdminOption::get('auth.attempt');
    }

    protected static function getClosureCheckAuth() {
        return AdminOption::get('auth.check');
    }

    protected static function checkAuth($checkClosure) {
        return is_callable($checkClosure) ? $checkClosure() : false;
    }

    public static function check() {
        return self::checkAuth(self::getClosureCheckAuth());
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
        return \Mascame\Artificer\Auth\ArtificerUser::where('email', '=', Input::get('username'))
            ->OrWhere('username', '=', Input::get('username'))->first();
    }

    /**
     * @param $user
     * @return bool
     */
    protected function attemptLogin($user)
    {
        $role_colum = AdminOption::get('auth.role_column');
        if (in_array($user->$role_colum, AdminOption::get('auth.roles'))) {

            $credentials = array(
                'email' => Input::get('username'),
                'password' => Input::get('password')
            );

            if (self::attempt(self::getClosureAttempt(), $credentials)) {
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


    public function authFilter() {
        $roles = AdminOption::get('auth.roles');
        $role_column = AdminOption::get('auth.role_column');

        if (Auth::guest()
            && \Route::currentRouteName() != 'admin.showlogin'
            && \Route::currentRouteName() != 'admin.login'
        ) {
            if (\Request::ajax()) {
                return \Response::make('Unauthorized', 401);
            } else {
                return Redirect::route('admin.showlogin');
            }
        } else {
            if (Auth::check()
                && \Route::currentRouteName() != 'admin.logout'
            ) {
                if (!in_array(Auth::user()->$role_column, $roles)) {
                    return Redirect::route('admin.logout');
                }
            }
        }
    }
}