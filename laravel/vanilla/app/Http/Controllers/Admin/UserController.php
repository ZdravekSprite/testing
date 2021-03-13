<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth.admin');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('admin.users.index')->with('users', User::paginate(10));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function show(User $user)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function edit(User $user)
  {
    if (Auth::user() == $user) {
      return redirect()->route('admin.users.index')->with('warning', 'You are not allowed to edit yourself.');
    }

    if (Auth::user()->hasAnyRole('admin') && $user->hasAnyRole('superadmin')) {
      return redirect()->route('admin.users.index')->with('warning', 'You are not allowed to edit superadmin.');
    }

    return view('admin.users.edit')->with(['user' => $user, 'roles' => Role::all()]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, User $user)
  {
    if (Auth::user() == $user) {
      return redirect()->route('admin.users.index')->with('warning', 'You are not allowed to update yourself.');
    }

    if (Auth::user()->hasAnyRole('admin') && $user->hasAnyRole('superadmin')) {
      return redirect()->route('admin.users.index')->with('warning', 'You are not allowed to update superadmin.');
    }

    $user->roles()->sync($request->roles);

    return redirect()->route('admin.users.index')->with('success', 'User has been updated.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user)
  {
    if (Auth::user() == $user) {
      return redirect()->route('admin.users.index')->with('warning', 'You are not allowed to delete yourself.');
    }

    if (Auth::user()->hasAnyRole('admin') && $user->hasAnyRole('superadmin')) {
      return redirect()->route('admin.users.index')->with('warning', 'You are not allowed to delete superadmin.');
    }

    if ($user) {
      $user->roles()->detach();
      $user->delete();
      return redirect()->route('admin.users.index')->with('success', 'This user has been deleted.');
    }

    return redirect()->route('admin.users.index')->with('warning', 'This user can not be deleted.');
  }
}
