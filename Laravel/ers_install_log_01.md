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

## Laravel Breeze

```
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate:fresh
git add .
git commit -am "new Laravel Breeze [ers_auth]"
php artisan serve
```

### day model (+ factory + migration + seeder + controller)
```
php artisan make:model Day -a
```

### database\migrations\2021_02_12_094805_create_days_table.php
```
  public function up()
  {
    Schema::create('days', function (Blueprint $table) {
      $table->id();
      $table->date('day');
      $table->unsignedBigInteger('user_id');
      $table->boolean('sick')->default(false);
      $table->time('start')->default('06:00:00');
      $table->time('duration')->default('08:00:00');
      $table->time('night_duration')->default(0);
      $table->timestamps();
      $table->unique(['user_id', 'day']);
      $table->foreign('user_id')->references('id')->on('users');
    });
  }
```

## Eloquent: Relationships

### app\Models\User.php
```
  /**
   * Get the users days.
   */
  public function days()
  {
    return $this->hasMany(Day::class);
  }
```

### app\Models\Day.php
```
  /**
   * Get the user that owns the day.
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
```

## Database: Seeding

### .env
```
ADMIN_PASS=password1234
```

### database\seeders\DatabaseSeeder.php
```
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Day;
use App\Models\User;

public function run()
  {
    DB::table('users')->insert([
      'name' => 'Zdravko Šplajt',
      'email' => 'zdravek.sprite@gmail.com',
      'password' => Hash::make(env('ADMIN_PASS', 'password')),
    ]);
    User::factory()
      ->count(10)
      ->create()->each(function ($user) {
        $days = Day::factory()->count(5)->make(['user_id' => $user->id]);
        foreach ($days as $day) {
          repeat:
          try {
            $day->save();
          } catch (\Illuminate\Database\QueryException $e) {
            $subject = Day::factory()->make(['user_id' => $user->id]);
            goto repeat;
          }
        }
      });
    /*$this->call([
      DaySeeder::class,
    ]);*/
  }
```

## Factory

### database\factories\DayFactory.php
```
  public function definition()
  {
    return [
      'user_id' => User::factory(),
      'day' => $this->faker->dateTimeThisYear(),
    ];
  }
```

### app\Http\Controllers\DayController.php
```
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    $days = Day::orderBy('day','desc')->get();
    return view('days.index')->with('days', $days);
  }
```

### routes\web.php
```
use App\Http\Controllers\DayController;
Route::resource('days', DayController::class);
```

```
php artisan route:list
```

### resources\views\days\index.blade.php
```
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Days') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          Days index!
          @if(count($days) > 0)
          @foreach($days as $day)
          <div class="well">
          <span class="badge badge-light">{{$day->id}}.</span><h3><a href="/days/{{$day->id}}">{{$day->day}}</a></h3><span class="badge badge-info">{{$day->user->name}}</span>
          </div>
          @endforeach
          @else
          <p> No days found</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
```
```
php artisan migrate:fresh --seed
git add .
git commit -am "day index [ers_auth]"
```