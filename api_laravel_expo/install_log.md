# Laravel Expo

## Laravel

```bash
composer create-project --prefer-dist laravel/laravel laravel
```

### .gitignore

```txt
composer.lock
package-lock.json
```

### .editorconfig

```ts
end_of_line = crlf
indent_size = 2
```

### MySql

> - create laravel_api db

### .env

```ts
APP_NAME="Laravel Backend"
DB_DATABASE=laravel_api
```

```bash
cd laravel
npm install && npm run dev
php artisan migrate:fresh
php artisan serve
git add .
git commit -am "Initial Commit - Laravel Framework [api]"
```

## Laravel Sanctum

```bash
# composer require laravel/sanctum
# php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
# php artisan migrate
```

### laravel\app\Http\Kernel.php

```php
  protected $middlewareGroups = [
    'api' => [
      \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ],
  ];
```

```bash
php artisan make:controller AuthController
```

### laravel\routes\api.php

```php
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::post('/logout', [AuthController::class, 'logout']);
});
```

```bash
git add .
git commit -am "Laravel Sanctum, Api Auth Routes [api]"
```
