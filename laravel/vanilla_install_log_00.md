```
git checkout -b laravel
composer create-project --prefer-dist laravel/laravel vanilla
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
## MySql
> - create laravel_vanilla db
### .env
```
APP_NAME="Vanilla Laravel"
DB_DATABASE=laravel_vanilla
```
```
npm install && npm run dev
php artisan migrate:fresh
php artisan serve
git add .
git commit -am "Initial Commit - Laravel Framework Installed [laravel]"
```
## Laravel Breeze
```
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate:fresh
git add .
git commit -am "Laravel Breeze Installed [laravel]"
```
### routes\web.php
```
Route::get('/', function () {
    return view('welcome');
})->name('home');
```
### resources\views\welcome.blade.php
```
<x-guest-layout>
  <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
```
```  
  </div>
</x-guest-layout>
```
### resources\views\layouts\navigation.blade.php
```
        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center">
          <a href="{{ route('home') }}">
            <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
          </a>
        </div>
```
### resources\views\layouts\guest.blade.php
```
      <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}">
                  <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                </a>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
              </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
              @if (Route::has('login'))
              <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                @auth
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>

                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                @endif
                @endauth
              </div>
              @endif
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
              <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
          @if (Route::has('login'))
          @auth
          <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
              {{ __('Dashboard') }}
            </x-responsive-nav-link>
          </div>
          @else
          <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
              {{ __('Log in') }}
            </x-responsive-nav-link>
          </div>
          @if (Route::has('register'))
          <div class="pt-4 pb-1 border-t border-gray-200">
            <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
              {{ __('Register') }}
            </x-responsive-nav-link>
          </div>
          @endif
          @endauth
          @endif
        </div>
      </nav>
```
## Laravel Breeze
```
git add .
git commit -am "Costum fix [laravel]"
```
### day model (+ factory + migration + seeder + controller)
```
php artisan make:model Day -a
```
### database\migrations\2021_02_21_143135_create_days_table.php
```
  public function up()
  {
    Schema::create('days', function (Blueprint $table) {
      $table->id();
      $table->date('date');
      $table->unsignedBigInteger('user_id');
      $table->boolean('sick')->default(false);
      $table->time('start')->default('06:00:00');
      $table->time('duration')->default('08:00:00');
      $table->time('night_duration')->default(0);
      $table->timestamps();
      $table->unique(['user_id', 'date']);
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
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'id',
    'created_at',
    'updated_at',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'date' => 'datetime:d.m.Y',
    'sick' => 'boolean',
    'start' => 'datetime:H:i',
    'duration' => 'datetime:H:i',
    'night_duration' => 'datetime:H:i',
  ];

  /**
   * Get the user that owns the day.
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
```
## Factory
### database\factories\DayFactory.php
```
use App\Models\User;
  public function definition()
  {
    return [
      'user_id' => User::factory(),
      'date' => $this->faker->dateTimeThisYear(),
    ];
  }
```
## Database: Seeding
### .env
```
ADMIN_NAME=admin
ADMIN_EMAIL=admin@admin.com
ADMIN_PASS=password1234
```
### database\seeders\DatabaseSeeder.php
```
use Illuminate\Support\Facades\Hash;
use App\Models\Day;
use App\Models\User;

public function run()
  {
    User::factory()
      ->create([
        'name' => env('ADMIN_NAME', 'admin'),
        'email' => env('ADMIN_EMAIL', 'admin@admin.com'),
        'password' => Hash::make(env('ADMIN_PASS', 'password')),
      ])->each(function ($user) {
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
  }
```
```
php artisan migrate:fresh --seed
git add .
git commit -am "seeding [laravel]"
```