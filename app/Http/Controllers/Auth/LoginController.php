<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use App\Role;
use Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the Provider authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
	
    /**
     * Obtain the user information from Provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $social_user = Socialite::driver($provider)->user();
        $user = User::firstOrCreate([
            'email'=>$social_user->getEmail(),
        ]);
        if (!$user->name) {
            $user->name = $social_user->getName();
        }
        if (!$user[$provider."_id"]) {
            $user[$provider."_id"] = $social_user->getId();
        }
        if ($social_user->getAvatar()) {
            if (!$user->avatar) {
                $user->avatar = $social_user->getAvatar();
            }
            if (!$user[$provider."_avatar"]) {
                $user[$provider."_avatar"] = $social_user->getAvatar();
            }
        }
        $user->save();

        if (!$user->roles->pluck( 'name' )->contains( 'socialuser' )) {
            $socialUserRole = Role::where('name', 'socialuser')->first();
            $user->roles()->attach($socialUserRole);
        }

        Auth::Login($user,true);
        return redirect('/home');
    }
}
