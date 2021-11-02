<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
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
    $roles = Role::orderBy('name', 'desc')->get();
    //dd($roles);
    return view('roles.index')->with(compact('roles'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('roles.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'name' => 'required'
    ]);
    $role = new Role;
    $role->name = $request->input('name');
    $role->description = $request->input('description') ?? $role->description;
    $role->save();
    return redirect(route('admin.roles.show', $role))->with('success', 'Role Created');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Role  $role
   * @return \Illuminate\Http\Response
   */
  public function show(Role $role)
  {
    return view('roles.show')->with(compact('role'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Role  $role
   * @return \Illuminate\Http\Response
   */
  public function edit(Role $role)
  {
    return view('roles.edit')->with(compact('role'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Role  $role
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Role $role)
  {
    $this->validate($request, [
      'name' => 'required'
    ]);
    $role->name = $request->input('name');
    $role->description = $request->input('description') ?? $role->description;
    $role->save();
    return redirect(route('admin.roles.show', $role))->with('success', 'Role Updatet');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Role  $role
   * @return \Illuminate\Http\Response
   */
  public function destroy(Role $role)
  {
    $role->delete();
    return redirect(route('admin.roles.index'))->with('success', 'Role removed');
  }
}
