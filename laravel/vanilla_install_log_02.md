## Holidays

### holiday model (+ factory + migration + seeder + controller)
```
php artisan make:model Holiday -a
```
### database\migrations\2021_02_23_081405_create_holidays_table.php
```
  public function up()
  {
    Schema::create('holidays', function (Blueprint $table) {
      $table->id();
      $table->date('date')->unique();
      $table->string('name');
      $table->timestamps();
    });
  }
```
### app\Models\Holiday.php
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
  ];
```
### database\factories\HolidayFactory.php
```
  public function definition()
  {
    return [
      'date' => $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
      'name' => $this->faker->text($maxNbChars = 200),
    ];
  }
```
### database\seeders\HolidaySeeder.php
```
  public function run()
  {
    DB::table('holidays')->delete();

    $holidays = [
      ['date' => date('Y-m-d', strtotime('1.1.2020')), 'name' => 'Nova godina'],
      ['date' => date('Y-m-d', strtotime('6.1.2020')), 'name' => 'Sveta tri kralja (Bogojavljenje)'],
      ['date' => date('Y-m-d', strtotime('12.4.2020')), 'name' => 'Uskrs'],
      ['date' => date('Y-m-d', strtotime('13.4.2020')), 'name' => 'Uskrsni ponedjeljak'],
      ['date' => date('Y-m-d', strtotime('1.5.2020')), 'name' => 'Praznik rada'],
      ['date' => date('Y-m-d', strtotime('30.5.2020')), 'name' => 'Dan državnosti'],
      ['date' => date('Y-m-d', strtotime('11.6.2020')), 'name' => 'Tijelovo'],
      ['date' => date('Y-m-d', strtotime('22.6.2020')), 'name' => 'Dan antifašističke borbe'],
      ['date' => date('Y-m-d', strtotime('5.8.2020')), 'name' => 'Dan pobjede i domovinske zahvalnosti i Dan hrvatskih branitelja'],
      ['date' => date('Y-m-d', strtotime('15.8.2020')), 'name' => 'Velika Gospa'],
      ['date' => date('Y-m-d', strtotime('1.11.2020')), 'name' => 'Dan svih svetih'],
      ['date' => date('Y-m-d', strtotime('18.11.2020')), 'name' => 'Dan sjećanja na žrtve Domovinskog rata i Dan sjećanja na žrtvu Vukovara i Škabrnje'],
      ['date' => date('Y-m-d', strtotime('25.12.2020')), 'name' => 'Božić'],
      ['date' => date('Y-m-d', strtotime('26.12.2020')), 'name' => 'Sveti Stjepan'],
      ['date' => date('Y-m-d', strtotime('1.1.2021')), 'name' => 'Nova godina'],
      ['date' => date('Y-m-d', strtotime('6.1.2021')), 'name' => 'Sveta tri kralja (Bogojavljenje)'],
      ['date' => date('Y-m-d', strtotime('4.4.2021')), 'name' => 'Uskrs'],
      ['date' => date('Y-m-d', strtotime('5.4.2021')), 'name' => 'Uskrsni ponedjeljak'],
      ['date' => date('Y-m-d', strtotime('1.5.2021')), 'name' => 'Praznik rada'],
      ['date' => date('Y-m-d', strtotime('30.5.2021')), 'name' => 'Dan državnosti'],
      ['date' => date('Y-m-d', strtotime('3.6.2021')), 'name' => 'Tijelovo'],
      ['date' => date('Y-m-d', strtotime('22.6.2021')), 'name' => 'Dan antifašističke borbe'],
      ['date' => date('Y-m-d', strtotime('5.8.2021')), 'name' => 'Dan pobjede i domovinske zahvalnosti i Dan hrvatskih branitelja'],
      ['date' => date('Y-m-d', strtotime('15.8.2021')), 'name' => 'Velika Gospa'],
      ['date' => date('Y-m-d', strtotime('1.11.2021')), 'name' => 'Dan svih svetih'],
      ['date' => date('Y-m-d', strtotime('18.11.2021')), 'name' => 'Dan sjećanja na žrtve Domovinskog rata i Dan sjećanja na žrtvu Vukovara i Škabrnje'],
      ['date' => date('Y-m-d', strtotime('25.12.2021')), 'name' => 'Božić'],
      ['date' => date('Y-m-d', strtotime('26.12.2021')), 'name' => 'Sveti Stjepan'],
    ];

    Holiday::insert($holidays);
  }
```
### database\seeders\DatabaseSeeder.php
```
    $this->call([
      HolidaySeeder::class,
    ]);
```
```
php artisan migrate
php artisan db:seed --class=HolidaySeeder
```
### routes\web.php
```
use App\Http\Controllers\HolidayController;
Route::resource('holidays', HolidayController::class);
```
### app\Http\Controllers\HolidayController.php
```
  public function index()
  {
    $holidays = Holiday::orderBy('date', 'desc')->get();
    return view('holidays.index')->with('holidays', $holidays);
  }
```
### resources\views\holidays\index.blade.php
```
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Praznici') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          @if(count($holidays) > 0)
          @foreach($holidays as $day)
          <div class="container">
            {{$day->date->format('d.m.Y')}} {{$day->name}} 
          </div>
          @endforeach
          @else
          <p> No holidays found</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
```
### resources\views\layouts\navigation.blade.php
```
        <!-- Navigation Links -->
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('holidays.index')" :active="request()->routeIs('holidays.index')">
            {{ __('Praznici') }}
          </x-nav-link>
        </div>
  <!-- Responsive Navigation Menu -->
    <div class="pt-4 pb-1 border-t border-gray-200">
      <x-responsive-nav-link :href="route('holidays.index')" :active="request()->routeIs('holidays.index')">
        {{ __('Praznici') }}
      </x-responsive-nav-link>
    </div>
```
```
git add .
git commit -am "holidays [laravel]"
```