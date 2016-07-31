<?php

namespace Mascame\Artificer\Http\Controllers\Auth;

use App\User;
use Mascame\Artificer\Http\Controllers\BaseController;
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = \URL::route('admin.home');
        $this->guard = 'admin';

        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);

        parent::__construct();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:artificer_users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    protected function validate(\Illuminate\Http\Request $request, array $data)
    {
        // Allow email or username
        $field = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $this->username = $field;
        $request->merge([$field => $request->input('username')]);

        return Validator::make($data, [
            'username' => 'required|max:255',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function showLoginForm()
    {
        return view($this->getView('pages.login'));
    }

    // Todo
    public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }
}
