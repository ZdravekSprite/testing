<?php

use App\Http\Controllers\DayController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

//Route::resource('days', DayController::class);
Route::middleware('auth')->group(function () {
  Route::get('/days', [DayController::class, 'index'])
    ->name('days.index');
  Route::get('/days/create/{date}', [DayController::class, 'create']);

  Route::get('/day/create', [DayController::class, 'create'])
    ->name('day.create');
  Route::post('/day', [DayController::class, 'store'])
    ->name('day.store');
  Route::get('/day/{date}', [DayController::class, 'show'])
    ->name('day.show');
  Route::get('/day/edit/{date}', [DayController::class, 'edit'])
    ->name('day.edit');
  Route::post('/day/{date}', [DayController::class, 'update'])
    ->name('day.update');
  Route::delete('/day/{date}', [DayController::class, 'destroy'])
    ->name('day.destroy');

  Route::put('/sick/{date}', [DayController::class, 'sick'])
    ->name('sick');

  Route::resource('months', MonthController::class);

  Route::get('/month', [DayController::class, 'month'])
    ->name('month');
  Route::get('/month/print/{month?}', [MonthController::class, 'print'])
    ->name('print');
  Route::get('/month/{month}', [DayController::class, 'month']);
  //Route::get('/month/print/{month}', [DayController::class, 'print'])->name('print');

  Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

  //Route::get('/platna', [MonthController::class, 'platna_lista'])->name('platna');

  //Route::get('/lista', PlatnaLista::class)->name('lista');
  Route::get('/lista', [MonthController::class, 'platna_lista'])
    ->name('lista');
  Route::get('/lista/{month}', [MonthController::class, 'platna_lista']);
  //Route::put('/lista', [PlatnaLista::class, 'data']);
});

Route::resource('holidays', HolidayController::class);

Route::get('convert', function () {
  /*
  Day::where('sick', 1)
    ->update(['state' => 4]);
  Day::where('go', 1)
    ->update(['state' => 2]);
  Day::where('dopust', 1)
    ->update(['state' => 3]);
  Day::where('state', 2)
    ->update(['end' => '0:00:00']);
    Day::where('state', 3)
    ->update(['end' => '0:00:00']);
    Day::where('state', 4)
    ->update(['end' => '0:00:00']);
  
  Day::where('state', 0)
  ->where('duration', '22:40:00')
  ->update(['state' => 1,'end' => '22:40:00']);
  
  $days = Day::all();
  foreach ($days as $day) {
    //echo $day->name;
    //if ($day->sick) $day->state = 4;
    //if ($day->go) $day->state = 2;
    //if ($day->dopust) $day->state = 3;
    if ($day->duration) $day->end = $day->duration;
    if ($day->duration && !$day->state) $day->state = 1;
    if ($day->night_duration) $day->night = $day->night_duration;
    $day->save;
    dd($day);
  }
  dd($days, Day::all());
  */
  return 'Database days converted.';
})->middleware(['auth'])->name('convert');
