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

### app\Http\Controllers\DayController.php
```
use App\Models\Day;

public function index()
  {
    return Day::orderBy('created_at', 'DESC')->get();
  }
  
 public function store(Request $request)
  {
    $newDay = new Day;
    $newDay->day = $request->day["day"];
    $newDay->sick = isset($request->day["sick"]) ? $request->day["sick"] : false;
    $newDay->start = isset($request->day["start"]) ? $request->day["start"] : '6:00';
    $newDay->duration = isset($request->day["duration"]) ? $request->day["duration"] : '8:00';
    $newDay->night_duration = isset($request->day["night_duration"]) ? $request->day["night_duration"] : 0;
    $newDay->save();

    return $newDay;
  }

  public function update(Request $request, $day)
  {
    $existingDay = Day::where('day', '=', $day)->first(); //firstOrNew firstOrCreate
    if ($existingDay) {
      if (isset($request->day["sick"])) $existingDay->sick = $request->day["sick"];
      if (isset($request->day["start"])) $existingDay->start = $request->day["start"];
      if (isset($request->day["duration"])) $existingDay->duration = $request->day["duration"];
      if (isset($request->day["night_duration"])) $existingDay->night_duration = $request->day["night_duration"];
      $existingDay->save();
      return $existingDay;
    }

    return "Day not found.";
  }

  public function destroy($day)
  {
    $existingDay = Day::where('day', '=', $day)->first();
    if ($existingDay) {
      $existingDay->delete();
      return "Day successfully deleted.";
    }

    return "Day not found.";
  }
```

```
npm install
npm install vue
```

### resources\js\vue\app.vue
```
<template>
  <div>
    Hello
  </div>
</template>

<script>
export default {
  
}
</script>
```
### resources\js\app.js
```
require('./bootstrap');

import Vue from 'vue'

import App from './vue/app'

const app = new Vue({
  el: '#app',
  components: { App }
});
```
### webpack.mix.js
```
mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]).vue();
```
### resources\views\welcome.blade.php
```
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Laravel Vue</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

</head>

<body>
  <div id="app">
    <app></app>
  </div>
</body>
<script src="{{ mix('js/app.js')}}"></script>

</html>
```
```
npm run hot
git add .
git commit -am "Hello Vue ERS [laravel]"
```