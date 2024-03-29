<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $roles = Role::orderBy('name', 'desc')->get();
    return view('admin.roles.index')->with(compact('roles'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('admin.roles.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreRoleRequest $request)
  {
    $role = new Role;
    $role->name = $request->input('name');
    $role->description = $request->input('description') ?? $role->description;
    $role->save();
    return redirect(route('admin.roles.show', $role))->with('success', 'Role Created');
  }

  /**
   * Display the specified resource.
   */
  public function show(Role $role)
  {
    return view('admin.roles.show')->with(compact('role'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Role $role)
  {
    return view('admin.roles.edit')->with(compact('role'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateRoleRequest $request, Role $role)
  {
    $role->description = $request->input('description') ?? $role->description;
    $role->save();
    return redirect(route('admin.roles.show', $role))->with('success', 'Role Updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Role $role)
  {
    $role->users()->detach();
    $role->delete();
    return redirect(route('admin.roles.index'))->with('success', 'Role removed');
  }
}
