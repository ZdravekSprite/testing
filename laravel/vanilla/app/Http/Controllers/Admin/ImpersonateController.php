<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
  /**
   * Start Impersonate the specified User.
   *
   * @param  \App\User  $user
   * @return \Illuminate\Http\Response
   */
  public function start($id)
  {
    $user = User::where('id', $id)->first();
    if ($user) {
      session()->put('impersonate', Auth::id());
      Auth::login($user);
    }
    return redirect()->route('home');
  }

  /**
   * Stop Impersonate.
   *
   * @return \Illuminate\Http\Response
   */
  public function stop()
  {
    Auth::loginUsingId(session('impersonate'));
    session()->forget('impersonate');
    return redirect(route('home'));
  }
}
