<?php namespace Mascame\Artificer;

use View;
use Validator;
use Redirect;
use Input;
use Auth;
use Controller;
use Hash;

class UserController extends Artificer {

	public function showLogin()
	{
		// show the form
		return View::make($this->getView('pages.lockscreen'));
	}

	public function doLogin()
	{
		// process the form
		// validate the info, create rules for the inputs
		$rules = array(
			'email'    => 'required|email', // make sure the email is an actual email
			'password' => 'required|min:3' // password can only be alphanumeric and has to be greater than 3 characters
		);

		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);

		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			dd('failed');

			return Redirect::route('admin.showlogin')
				->withErrors($validator) // send back all errors to the login form
				->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
		} else {
			// create our user data for the authentication
			$userdata = array(
				'email'    => Input::get('email'),
				'password' => Hash::make(Input::get('password'))
			);

			// attempt to do the login
			if (Auth::attempt($userdata)) {
				dd('its ok');
				// validation successful!
				// redirect them to the secure section or whatever
				// return Redirect::to('secure');
				// for now we'll just echo success (even though echoing in a controller is bad)
				return Redirect::route('admin.home');
			} else {
				// Todo: This is not working ......... find the bug
				dd('its NOt ok' . print_r($userdata));

				// validation not successful, send back to form
				return Redirect::route('admin.login')
					->withInput(Input::except('password'));
//                ->with('auth-error-message', 'U heeft een onjuiste gebruikersnaam of een onjuist wachtwoord ingevoerd.');

//                return Redirect::route('admin.showlogin')
//                    ->withErrors($validator) // send back all errors to the login form
//                    ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
			}

		}
	}

	public function doLogout()
	{
		Auth::logout(); // log the user out of our application
		return Redirect::route('admin.showlogin'); // redirect the user to the login screen
	}

}