```
php artisan serve
npm run hot
```
### routes\web.php
```
use App\Http\Controllers\DayController;
Route::resource('days', DayController::class);
```
### app\Http\Controllers\DayController.php
```
<?php

namespace App\Http\Controllers;

use App\Models\Day;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DayController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $days = Day::orderBy('date','desc')->where('user_id', '=', Auth::user()->id)->get();
    return view('days.index')->with('days', $days);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('days.create'); 
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Day  $day
   * @return \Illuminate\Http\Response
   */
  //public function show(Day $day)
  public function show($date)
  {
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d',strtotime($date)))->get();
    //dd($day);
    return view('days.show')->with('day', $day); 
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Day  $day
   * @return \Illuminate\Http\Response
   */
  //public function edit(Day $day)
  public function edit($date)
  {
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d',strtotime($date)))->get();
    //dd($day);
    return view('days.edit')->with('day', $day); 
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Day  $day
   * @return \Illuminate\Http\Response
   */
  //public function update(Request $request, Day $day)
  public function update(Request $request, $date)
  {
  //dd($request);
  $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d',strtotime($date)))->get();
  if (null != $request->input('sick')) $day[0]->sick = $day[0]->sick;
  $day[0]->night_duration = $request->input('night_duration') ? $request->input('night_duration') : $day[0]->night_duration;
  $day[0]->start = $request->input('start');
  $day[0]->duration = $request->input('duration');
  $day[0]->save();
  return redirect(route('days.show' , ['day' => $day[0]->date->format('d.m.Y')]))->with('success', 'Day Updated'); 
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Day  $day
   * @return \Illuminate\Http\Response
   */
  public function destroy(Day $day)
  {
    $day->delete();
    return redirect(route('days.index'))->with('success', 'Day removed');
  }
}

```
### resources\views\days\index.blade.php
```
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Evidencija radnih sati') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          Evidencija radnih sati!
          @if(count($days) > 0)
          @foreach($days as $day)
          <div class="container">
            <a class="float-left" href="/days/{{$day->date->format('d.m.Y')}}">{{$day->date->format('d.m.Y')}}</a>
            <div class="float-left relative bg-indigo-100" style="width: 75%; min-height: 18px;" title={{$day->date->format('d.m.Y')}}>
              <div class="absolute bg-indigo-700 min-h-full" style="width: {{($day->night_duration->hour*60 + $day->night_duration->minute)/1440*100}}%;"></div>
              <div class="absolute bg-indigo-500 min-h-full" style="margin-left: {{($day->start->hour*60 + $day->start->minute)/1440*100}}%; width: {{($day->duration->hour*60 + $day->duration->minute)/1440*100}}%;"></div>
            </div>
            <a href="/days/{{$day->date->format('d.m.Y')}}/edit">edit</a>
            <i class="icon-trash"></i>
            <a style="color:black" href="{{ route('days.destroy', ['day' => $day]) }}" onclick="event.preventDefault();
    document.getElementById('delete-form-{{ $day->date->format('d.m.Y') }}').submit();">
              delete
            </a>

            <form id="delete-form-{{ $day->date->format('d.m.Y') }}" action="{{ route('days.destroy', ['day' => $day]) }}" method="POST" style="display: none;">
              @csrf
            @method('DELETE')
            </form>
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
### resources\views\days\show.blade.php
```
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dan') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <h3>Day {{ $day[0]->date->format('d.m.Y') }}!</h3>
          <p>{{ $day[0]->sick ? 'bio sam' : 'nisam bio' }} na bolovanju</p>
          <p>od ponoći {{ $day[0]->night_duration->hour > 0 ? 'sam radio ' + $day[0]->night_duration->format('H:i') + 'sati' : 'nisam radio' }}</p>
          <p>smjena je započela u {{ $day[0]->start->format('H:i') }} sati</p>
          <p>radio sam {{ $day[0]->duration->format('H:i') }} sati</p>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
```
### resources\views\days\edit.blade.php
```
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Uredi dan') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          Edit {{$day[0]->date->format('d.m.Y')}} day!
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('days.update' , ['day' => $day[0]->date->format('d.m.Y')]) }}">
            @csrf
            @method('PUT')

            <!-- date -->
            <input id="date" class="hidden" type="date" name="date" value={{$day[0]->date->format('Y-m-d')}} required autofocus />

            <!-- bolovanje -->
            <div class="mt-4">
              <x-label for="sick" :value="__('Bolovanje')" />
              <x-input id="sick" class="block mt-1 w-full" type="checkbox" name="sick" :value="old('sick')" />
            </div>

            <!-- nocna -->
            <div class="mt-4">
              <x-label for="night_duration" :value="__('Rad od ponoći')" />
              <input id="night_duration" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="night_duration" value={{$day[0]->night_duration->format('H:i')}} required />
            </div>

            <!-- pocetak -->
            <div class="mt-4">
              <x-label for="start" :value="__('Početak smjene')" />
              <input id="start" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="start" value={{$day[0]->start->format('H:i')}} required />
            </div>

            <!-- duzina -->
            <div class="mt-4">
              <x-label for="duration" :value="__('Dužina rada')" />
              <input id="duration" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="duration" value={{$day[0]->duration->format('H:i')}} required />
            </div>

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
### resources\views\days\create.blade.php
```
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Novi dan') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          Create new!
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('days.update' , ['day' => $day[0]->date->format('d.m.Y')]) }}">
            @csrf
            @method('PUT')

            <!-- date -->
            <div class="mt-4">
              <x-label for="date" :value="__('Dan')" />
            <input id="date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="date" name="date" value={{$day[0]->date->format('Y-m-d')}} required autofocus />
            </div>

            <!-- bolovanje -->
            <div class="mt-4">
              <x-label for="sick" :value="__('Bolovanje')" />
              <x-input id="sick" class="block mt-1 w-full" type="checkbox" name="sick" :value="old('sick')" />
            </div>

            <!-- nocna -->
            <div class="mt-4">
              <x-label for="night_duration" :value="__('Rad od ponoći')" />
              <input id="night_duration" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="night_duration" value={{$day[0]->night_duration->format('H:i')}} required />
            </div>

            <!-- pocetak -->
            <div class="mt-4">
              <x-label for="start" :value="__('Početak smjene')" />
              <input id="start" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="start" value={{$day[0]->start->format('H:i')}} required />
            </div>

            <!-- duzina -->
            <div class="mt-4">
              <x-label for="duration" :value="__('Dužina rada')" />
              <input id="duration" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="duration" value={{$day[0]->duration->format('H:i')}} required />
            </div>

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
### resources\views\layouts\navigation.blade.php
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
          <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
          </x-nav-link>
        </div>
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('days.index')" :active="request()->routeIs('days.index', 'days.create', 'days.show', 'days.edit')">
            {{ __('ERS') }}
          </x-nav-link>
        </div>
        <div class="hidden sm:flex sm:items-center sm:ml-6">
          <x-dropdown align="left">
            <x-slot name="trigger">
              <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                <div class="ml-1">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </div>
              </button>
            </x-slot>
            <x-slot name="content">
              <div class="pt-2 pb-1 border-t border-gray-200">
                <x-nav-link :href="route('days.index')" :active="request()->routeIs('days.index')">
                  {{ __('Radni dani') }}
                </x-nav-link>
              </div>
              <div class="pt-2 pb-1 border-t border-gray-200">
                <x-nav-link :href="route('days.create')" :active="request()->routeIs('days.create')">
                  {{ __('Novi dan') }}
                </x-nav-link>
              </div>
            </x-slot>
          </x-dropdown>
        </div>
      </div>

      <!-- Settings Dropdown -->
      <div class="hidden sm:flex sm:items-center sm:ml-6">
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
              <div>{{ Auth::user()->name }}</div>

              <div class="ml-1">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </div>
            </button>
          </x-slot>

          <x-slot name="content">
            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
              @csrf

              <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                {{ __('Log out') }}
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
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
    <div class="pt-2 pb-3 space-y-1">
      <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
      </x-responsive-nav-link>
    </div>
    <div class="pt-4 pb-1 border-t border-gray-200">
      <x-responsive-nav-link :href="route('days.index')" :active="request()->routeIs('days.index')">
        {{ __('ERS') }}
      </x-responsive-nav-link>
    </div>

    <!-- Responsive Settings Options -->
    <div class="pt-4 pb-1 border-t border-gray-200">
      <div class="flex items-center px-4">
        <div class="flex-shrink-0">
          <svg class="h-10 w-10 fill-current text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </div>

        <div class="ml-3">
          <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
          <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
        </div>
      </div>

      <div class="mt-3 space-y-1">
        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
          @csrf

          <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
            {{ __('Log out') }}
          </x-responsive-nav-link>
        </form>
      </div>
    </div>
  </div>
</nav>
```
```
git add .
git commit -am "route controller views [laravel]"
```