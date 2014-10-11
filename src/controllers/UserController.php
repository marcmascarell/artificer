<?php namespace Mascame\Artificer;

use Mascame\Artificer\Options\AdminOption;
use View;
use Validator;
use Redirect;
use Input;
use Auth;
use Carbon\Carbon;
use Session;

class UserController extends BaseController {

    public $max_tries = 3;
    public $tries_key = 'artificer.user.login.tries';
    public $ban_time = 5;
    public $ban_key = 'artificer.user.login.banned';

    public function isBanned() {
        if (Session::has($this->ban_key)) {
            $ban = Carbon::parse(Session::get($this->ban_key));

            if (!$ban->isPast()) {
                return true;
            }
        }

        Session::forget($this->ban_key);

        return false;
    }

    public function ban() {
        Session::set($this->ban_key, Carbon::now()->addMinutes($this->ban_time));
    }


    public function addAttempt() {
        $tries = Session::get($this->tries_key);

        if (!$tries) {
            $tries = 1;
        } else {
            $tries++;
        }

        Session::set($this->tries_key, $tries);

        if ($tries >= $this->max_tries) {
            $this->ban();
            Session::forget($this->tries_key);
        }
    }

	public function showLogin()
	{
		if (Auth::check()) return Redirect::route('admin.home');

		return View::make($this->getView('pages.login'));
	}

	public function login()
	{
        if ($this->isBanned()) {
            return Redirect::route('admin.showlogin')
                ->withErrors(array("You are banned for too many login attempts"));
        }

		$rules = array(
			'username' => 'required|email',
			'password' => 'required|min:3'
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
            $this->addAttempt();

			return Redirect::route('admin.showlogin')
				->withErrors($validator)
				->withInput();
		}

		$user = \User::where('email', '=', Input::get('username'))->first();

        if ($user) {
			$role_colum = AdminOption::get('auth.role_column');

            if (in_array($user->$role_colum, AdminOption::get('auth.roles'))) {

                $userdata = array(
                    'email'    => Input::get('username'),
                    'password' => Input::get('password')
                );

                if (Auth::attempt($userdata)) {
                    return Redirect::route('admin.home');
                }
            }
        }

		return Redirect::route('admin.login')
			->withInput(Input::except('password'))->withErrors($validator);
	}

	public function logout()
	{
		Auth::logout();

		return Redirect::route('admin.showlogin');
	}

}