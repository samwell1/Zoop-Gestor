<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
	 /**
 * Check user's role and redirect user based on their role
 * @return 
 */
public function authenticated()
{
    if(auth()->user()->hasRole('admin'))
    {
        return redirect()->route('home');
    } 
	
	if(auth()->user()->hasRole('repositor'))
	{
	return redirect()->route('user_home');
	}

    //return redirect('/user/dashboard');
}

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
