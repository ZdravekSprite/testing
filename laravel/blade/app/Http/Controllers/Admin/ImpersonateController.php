<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ImpersonateController extends Controller
{
  public function start($id)
  {
    $user = User::where('id', $id)->first();
    if ($user) {
      session()->put('impersonate', $user->id);
    }
    return redirect()->route('home');
  }
  public function stop()
  {
    session()->forget('impersonate');
    return redirect(route('home'));
  }
}
