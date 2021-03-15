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
          <table class="table-auto w-full">
            <thead>
              <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Roles') }}</th>
                <th>{{ __('Actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              <tr>
                <th>{{$user->name}}</th>
                <td>{{$user->email}}</td>
                <td>{{ implode(', ', $user->roles()->get()->pluck('name')->toArray()) }}</td>
                <td>
                  <a class="float-left" href="{{ route('admin.users.edit', $user->id) }}" title="{{ __('Izmjeni') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                      <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
                    </svg>
                  </a>
                  <a class="float-right" style="color:black" href="{{ route('admin.users.destroy', $user) }}" onclick="event.preventDefault();
    document.getElementById('delete-form-{{ $user->id }}').submit();" title="Izbriši">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                    </svg>
                  </a>
                  <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                  </form>
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
### routes\web.php
```php
use App\Http\Controllers\Admin\UserController;
Route::prefix('admin')->middleware(['auth', 'auth.admin'])->name('admin.')->group(function () {
  Route::resource('/users', UserController::class, ['except' => ['show', 'create', 'store']]);
});
```
```bash
git add .
git commit -am "Laravel AccessAdmin v0.8a [laravel]"
```
### resources\views\admin\users\edit.blade.php
```php
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Manage korisnika!')}}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          {{ __('Manage') .' '. $user->name }}
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <!-- role -->
            @foreach ($roles as $role)
            <div class="mt-4">
              <label for="{{ $role->name }}" class="block font-medium text-sm text-gray-700">{{ $role->name }}</label>
              <input id="{{ $role->name }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" name="roles[]" value="{{ $role->id }}" {{ $user->hasAnyRole($role->name)?'checked':'' }}>
            </div>
            @endforeach

            <div class="flex items-center justify-end mt-4">
              <x-button class="ml-4">
                {{ __('Spremi') }}
              </x-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
```
### resources\views\dashboard.blade.php
```php
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          @hasrole('admin')
          <p>
            <a href="{{ route('migrate') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
              </svg>
              migrate
            </a>
          </p>
          <p>
            <a href="{{ route('rollback') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
              </svg>
              rollback
            </a>
          </p>
          @endhasrole
          @hasrole('superadmin')
          <p>You're super admin!</p>
          @else
          <p>You're logged in!</p>
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('lista') }}">
            @csrf
            @method('PUT')
            <!-- bruto -->
            <div class="mt-4">
              <x-label for="bruto" :value="__('Bruto')" />
              <input id="bruto" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="bruto" value="{{Auth::user()->bruto ? Auth::user()->bruto : old('bruto')?? 5300}}" min="4250" step="50" />
            </div>
            <!-- prijevoz -->
            <div class="mt-4">
              <x-label for="prijevoz" :value="__('Prijevoz')" />
              <input id="prijevoz" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="prijevoz" value="{{Auth::user()->prijevoz ? Auth::user()->prijevoz : old('prijevoz')?? 360}}" min="0" step="10" />
            </div>
            <!-- odbitak -->
            <div class="mt-4">
              <x-label for="odbitak" :value="__('Odbitak')" />
              <input id="odbitak" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="odbitak" value="{{Auth::user()->odbitak ? Auth::user()->odbitak : old('odbitak')?? 4000}}" min="4000" step="50" />
              <div class="ml-12 mt-2 text-gray-600 dark:text-gray-400 text-sm">
                <a href="https://www.porezna-uprava.hr/baza_znanja/Stranice/OsobniOdbitak.aspx" class="underline text-gray-900 dark:text-white">OSOBNI ODBITAK</a>
              </div>
            </div>
            <!-- prirez -->
            <div class="mt-4">
              <x-label for="prirez" :value="__('Prirez')" />
              <input id="prirez" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="prirez" value="{{Auth::user()->prirez ? Auth::user()->prirez/10 : old('prirez')?? 18}}" min="0" step="0.5" />
              <div class="ml-12 mt-2 text-gray-600 dark:text-gray-400 text-sm">
                <a href="https://www.porezna-uprava.hr/HR_porezni_sustav/Stranice/Popisi/Stope.aspx" class="underline text-gray-900 dark:text-white">PRIREZ</a>
              </div>
            </div>
            <!-- zaposlen -->
            <div class="mt-4">
              <x-label for="zaposlen" :value="__('Zaposlen od')" />
              <input id="zaposlen" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="date" name="zaposlen" value="{{Auth::user()->zaposlen ? Auth::user()->zaposlen : old('zaposlen')}}" />
              <div class="ml-12 mt-2 text-gray-600 dark:text-gray-400 text-sm">
                Da bi se mogao točno izračunati prvi mjesec rada ako se nije zaposlilo prvog u mjesecu.
              </div>
            </div>
            <div class="flex items-center justify-end mt-4">
              <x-button class="ml-4">
                {{ __('Spremi') }}
              </x-button>
            </div>
          </form>
          @endhasrole
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
```
### resources\views\layouts\navigation.blade.php
```php
        @hasrole('superadmin')
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
            {{ __('Menage Users') }}
          </x-nav-link>
        </div>
        @endhasrole
    @hasrole('superadmin')
    <div class="pt-4 pb-1 border-t border-gray-200">
      <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
        {{ __('Menage Users') }}
      </x-responsive-nav-link>
    </div>
    @endhasrole
```
```bash
git add .
git commit -am "Laravel AccessAdmin v0.8b [laravel]"
git commit -am "Laravel AccessAdmin v0.8 [laravel]"
```
