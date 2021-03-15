# Laravel Impersonate
```bash
php artisan make:controller Admin\\ImpersonateController
```
### app\Http\Controllers\Admin\ImpersonateController.php
```php
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
```
### routes\web.php
```php
use App\Http\Controllers\Admin\ImpersonateController;
Route::get('admin/impersonate/stop', [ImpersonateController::class, 'stop'])->name('admin.impersonate.stop');
Route::prefix('admin')->middleware(['auth', 'auth.admin'])->name('admin.')->group(function () {
  Route::resource('/users', UserController::class, ['except' => ['show', 'create', 'store']]);
  Route::get('/impersonate/{id}', [ImpersonateController::class, 'start'])->name('impersonate.start');
});
```
```bash
php artisan make:middleware Impersonate
```
### app\Http\Middleware\Impersonate.php
```php
use Illuminate\Support\Facades\Auth;
  public function handle(Request $request, Closure $next)
  {
    if (session()->has('impersonate')) {
      Auth::onceUsingId(session('impersonate'));
    }
    return $next($request);
  }
```
## app\Http\Kernel.php
40+
```php
            \App\Http\Middleware\Impersonate::class,
```
### app\Providers\BladeServiceProvider.php
```php
    Blade::if('impersonate', function () {

      if (session()->get('impersonate')) {
        return true;
      }

      return false;
    });
```
### resources\views\layouts\navigation.blade.php
```php
        @impersonate
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <a class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out" href="{{ route('admin.impersonate.stop') }}">{{ __('Stop Impersonating') }}</a>
        </div>
        @endimpersonate
```
### resources\views\admin\users\index.blade.php
```php
                  @hasrole('superadmin')
                  <a href="{{ route('admin.impersonate.start', $user->id) }}" class="float-left">
                    <button type="button" class="btn btn-success btn-sm">{{ __('Impersonate') }}</button>
                  </a>
                  @endhasrole
```
```bash
git add .
git commit -am "Laravel Impersonate v0.9 fix [laravel]"
```