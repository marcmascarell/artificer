<?php namespace Mascame\Artificer;

use View;
use Validator;
use Redirect;
use Input;
use Auth;

class UserController extends Artificer {

	public function showLogin()
	{
		return View::make($this->getView('pages.login'));
	}

	public function login()
	{
		$rules = array(
			'username' => 'required|email',
			'password' => 'required|min:3'
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::route('admin.showlogin')
				->withErrors($validator)
				->withInput();
		}

		$userdata = array(
			'email'    => Input::get('username'),
			'password' => Input::get('password')
		);

		if (Auth::attempt($userdata)) {
			return Redirect::intended('admin.home');
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