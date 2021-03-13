# Laravel AccessAdmin
```bash
php artisan make:middleware AccessAdmin
```
## app\Http\Middleware\AccessAdmin.php
```php
use Illuminate\Support\Facades\Auth;
  public function handle(Request $request, Closure $next)
  {
    if (Auth::user()->hasAnyRoles(['superadmin', 'admin'])) {
      return $next($request);
    }

    return redirect('home');
  }
```
## app\Http\Kernel.php
66+
```php
        'auth.admin' => \App\Http\Middleware\AccessAdmin::class,
```
```bash
php artisan make:controller Admin\\UserController -mUser
```
## app\Http\Controllers\Admin\UserController.php

```php
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

  public function __construct()
  {
    $this->middleware('auth.admin');
  }

  public function index()
  {
    return view('admin.users.index')->with('users', User::paginate(10));
  }

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
```
### resources\views\admin\users\index.blade.php
```php
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Manage Users') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Email') }}</th>
                <th scope="col">{{ __('Roles') }}</th>
                <th scope="col">{{ __('Actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              <tr>
                <th scope="row">{{$user->name}}</th>
                <td>{{$user->email}}</td>
                <td>{{ implode(', ', $user->roles()->get()->pluck('name')->toArray()) }}</td>
                <td>
                  <a href="{{ route('admin.users.edit', $user->id) }}">
                    <button type="button" class="btn btn-primary btn-sm">{{ __('Edit') }}</button>
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $users->links() }}
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
```
### resources\views\dashboard.blade.php
```php
Route::namespace('Admin')->prefix('admin')->middleware(['auth', 'auth.admin'])->name('admin.')->group(function(){
  Route::resource('/users', 'UserController', ['except' => ['show', 'create', 'store']]);
});
```