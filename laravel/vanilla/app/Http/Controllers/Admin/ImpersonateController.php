<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
      session()->put('impersonate', $user->id);
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
    session()->forget('impersonate');
    return redirect(route('home'));
  }
}
