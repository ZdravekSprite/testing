### u mysql CREATE DATABASE laravel_ers;
```
composer create-project --prefer-dist laravel/laravel ers
```
### .gitignore
```
composer.lock
package-lock.json
```
### -> .editorconfig
```
indent_size = 2
```
### .env
```
APP_NAME="Laravel ERS"
DB_DATABASE=laravel_ers
```

```
git add .
git commit -am "Initial Commit - Laravel Framework Installed ERS [laravel]"
php artisan serve
php artisan make:model Day -a
```
### database\migrations\2021_02_12_094805_create_days_table.php
```
  public function up()
  {
    Schema::create('days', function (Blueprint $table) {
      $table->id();
      $table->date('day')->unique();
      $table->boolean('sick')->default(false);
      $table->time('start')->default('06:00:00');
      $table->time('duration')->default('08:00:00');
      $table->time('night_duration')->default(0);
      $table->timestamps();
    });
  }
```

```
php artisan migrate
git add .
git commit -am "migrate ERS [laravel]"
```

### routes\api.php
```
use App\Http\Controllers\DayController;
Route::get('/days', [DayController::class, 'index']);
Route::prefix('/day')->group(function () {
  Route::post('/store', [DayController::class, 'store']);
  Route::post('/{id}', [DayController::class, 'update']);
  Route::delete('/{id}', [DayController::class, 'destroy']);
});
```

```
git commit -am "route ERS [laravel]"
```


--- npm install && npm run dev && npm run watch
