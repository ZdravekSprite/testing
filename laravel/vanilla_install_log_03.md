```
php artisan serve
npm run hot
```
### routes\web.php
```
Route::resource('days', DayController::class);
Route::resource('holidays', HolidayController::class);
Route::get('/month', [DayController::class, 'month'])->name('month');
Route::get('/month/{month}', [DayController::class, 'month']);
Route::get('/days/create/{date}', [DayController::class, 'create']);
```
### app\Http\Controllers\DayController.php
```
<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

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
    $_month['x'] = Carbon::now();
    $_month['-'] = Carbon::parse($_month['x'])->subMonthsNoOverflow();
    $_month['+'] = Carbon::parse($_month['x'])->addMonthsNoOverflow();
    $days = Day::orderBy('date', 'desc')->where('user_id', '=', Auth::user()->id)->get();
    return view('days.index')->with(compact('_month', 'days'));
  }
  /**
   * Display a listing of the resource.
   *
   * @param  $month
   * @return \Illuminate\Http\Response
   */
  public function month($month = null)
  {
    //dd($month);
    if ($month == null) {
      $_month['x'] = Carbon::now();
    } else {
      $_month['x'] = Carbon::parse('01.' . $month);
      //dd($month);
    }
    $_month['-'] = Carbon::parse($_month['x'])->subMonthsNoOverflow();
    $_month['+'] = Carbon::parse($_month['x'])->addMonthsNoOverflow();
    $from = CarbonImmutable::parse($_month['x'])->firstOfMonth();
    $to = Carbon::parse($_month['x'])->endOfMonth();

    $daysColection = Day::whereBetween('date', [$from, $to])->where('user_id', '=', Auth::user()->id)->get();
    $holidaysColection = Holiday::whereBetween('date', [$from, $to])->get();

    $datesArray = array();
    for ($i = 0; $i < $from->daysInMonth; $i++) {
      if ($daysColection->where('date', '=', $from->addDays($i))->first() != null) {
        $temp_date = $daysColection->where('date', '=', $from->addDays($i))->first();
      } else {
        $temp_date = new Day;
        $temp_date->date = $from->addDays($i);
        //dd($temp_date);
      }
      //$temp_date = $from->addDays($i);
      if ($holidaysColection->where('date', '=', $from->addDays($i))->first() != null) {
        //dd($holidaysColection->where('date', '=', $from->addDays($i))->first());
        $temp_date->holiday = $holidaysColection->where('date', '=', $from->addDays($i))->first()->name;
      }
      $datesArray[] = $temp_date;
    }
    $days = $datesArray;
    //dd($datesArray, $days);
    //dd($month, $from, $to);

    return view('days.index')->with(compact('_month', 'days'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  //public function create()
  public function create(Request $request)
  {
    $day = new Day;
    //dd($request->input('date'));
    if (null != $request->input('date')) {
      $day->date = $request->input('date');
      if ($request->input('sick') == true) {
        $day->sick = true;
      } else {
        if ($request->input('start') != null) $day->start = $request->input('start');
      }
    }
    //dd($day);
    return view('days.create')->with(compact('day'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'date' => 'required'
    ]);
    //dd($request);
    $old_day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($request->input('date'))))->get();
    $day = new Day;
    $day->date = $request->input('date');
    $day->user_id = Auth::user()->id;
    if (null != $request->input('sick')) $day->sick = $request->input('sick') == 'on' ? true : false;
    if (null != $request->input('night_duration')) $day->night_duration = $request->input('night_duration') ? $request->input('night_duration') : $day->night_duration;
    $day->start = $request->input('start');
    $day->duration = $request->input('duration');
    //dd($old_day);
    //dd($day);
    if (count($old_day) > 0) return view('days.edit')->with(compact('old_day', 'day'));
    $day->save();
    return redirect(route('days.show', ['date' => $day->date->format('d.m.Y')]))->with('success', 'Day Updated');
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
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->get();
    if (count($day) == 0) return redirect(route('month'));
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
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->get();
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
    $day = Day::where('user_id', '=', Auth::user()->id)->where('date', '=', date('Y-m-d', strtotime($date)))->get();
    if (null != $request->input('sick')) $day[0]->sick = $request->input('sick') == 'on' ? true : false;
    $day[0]->night_duration = $request->input('night_duration') ? $request->input('night_duration') : $day[0]->night_duration;
    $day[0]->start = $request->input('start');
    $day[0]->duration = $request->input('duration');
    $day[0]->save();
    return redirect(route('days.show', ['day' => $day[0]->date->format('d.m.Y')]))->with('success', 'Day Updated');
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
          <div class="flex justify-center">
            <a href="{{route('month').'/'.$_month['-']->format('m.Y')}}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
              </svg>
            </a>
            <a class="mx-auto" href="{{ route('month').'/'.$_month['x']->format('m.Y') }}">
              {{$_month['x']->format('m.Y')}}
            </a>
            <a href="{{route('month').'/'.$_month['+']->format('m.Y')}}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
              </svg>
            </a>
          </div>
          @if(count($days) > 0)
          @foreach($days as $day)
          <div class="container">
            <a class="float-left clear-left{{isset($day->holiday) ? ' text-red-400' : ''}}" href="/days/{{$day->date->format('d.m.Y')}}" title="{{$day->date->format('d.m.Y')}}{{isset($day->holiday) ? ' '.$day->holiday : ''}}">{{$day->date->format('d.m.Y')}}</a>
            @if(isset($day->sick))
            <div class="float-left rounded-md relative {{$day->sick ? 'bg-red-100' : 'bg-indigo-100'}}" style="width: 80%; min-height: 18px;" title={{$day->date->format('d.m.Y')}}>
              <div class="absolute bg-indigo-700 min-h-full" style="width: {{($day->night_duration->hour*60 + $day->night_duration->minute)/1440*100}}%;"></div>
              <div class="absolute bg-indigo-500 min-h-full" style="margin-left: {{($day->start->hour*60 + $day->start->minute)/1440*100}}%; width: {{($day->duration->hour*60 + $day->duration->minute)/1440*100}}%;"></div>
            </div>
            <a class="float-left" href="/days/{{$day->date->format('d.m.Y')}}/edit" title="Izmjeni">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
              </svg>
            </a>
            <a class="float-right" style="color:black" href="{{ route('days.destroy', ['day' => $day]) }}" onclick="event.preventDefault();
    document.getElementById('delete-form-{{ $day->date->format('d.m.Y') }}').submit();" title="Izbriši">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
              </svg>
            </a>

            <form id="delete-form-{{ $day->date->format('d.m.Y') }}" action="{{ route('days.destroy', ['day' => $day]) }}" method="POST" style="display: none;">
              @csrf
              @method('DELETE')
            </form>
            @else
            <div class="float-left relative bg-yellow-100" style="width: 80%; min-height: 18px;">
            </div>
            <a class="float-left" href="{{ route('days.create', ['date' => $day->date->format('d.m.Y')]) }}" title="Dodaj">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-plus" viewBox="0 0 16 16">
                <path d="M8 7a.5.5 0 0 1 .5.5V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5A.5.5 0 0 1 8 7z" />
                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
              </svg>
            </a>
            <a class="float-left mx-1" href="{{ route('days.create', ['date' => $day->date->format('d.m.Y'), 'start' => '06:00']) }}" title="1.smjena">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-sunrise" viewBox="0 0 16 16">
                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l1.5 1.5a.5.5 0 0 1-.708.708L8.5 2.707V4.5a.5.5 0 0 1-1 0V2.707l-.646.647a.5.5 0 1 1-.708-.708l1.5-1.5zM2.343 4.343a.5.5 0 0 1 .707 0l1.414 1.414a.5.5 0 0 1-.707.707L2.343 5.05a.5.5 0 0 1 0-.707zm11.314 0a.5.5 0 0 1 0 .707l-1.414 1.414a.5.5 0 1 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zM8 7a3 3 0 0 1 2.599 4.5H5.4A3 3 0 0 1 8 7zm3.71 4.5a4 4 0 1 0-7.418 0H.499a.5.5 0 0 0 0 1h15a.5.5 0 0 0 0-1h-3.79zM0 10a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2A.5.5 0 0 1 0 10zm13 0a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z" />
              </svg>
            </a>
            <a class="float-left" href="{{ route('days.create', ['date' => $day->date->format('d.m.Y'), 'start' => '14:00']) }}" title="2.smjena">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-sun" viewBox="0 0 16 16">
                <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
              </svg>
            </a>
            @endif
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
          Edit {{$day->date->format('d.m.Y')}} day!
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('days.update' , ['day' => $day->date->format('d.m.Y')]) }}">
            @csrf
            @method('PUT')

            <!-- date -->
            <input id="date" class="hidden" type="date" name="date" value={{$day->date->format('Y-m-d')}} required autofocus />

            <!-- bolovanje -->
            <div class="mt-4">
              <x-label for="sick" :value="__('Bolovanje')" />
              <x-input id="sick" class="block mt-1 w-full" type="checkbox" name="sick" :value="old('sick')" />
            </div>

            <!-- nocna -->
            <div class="mt-4">
              <x-label for="night_duration" :value="__('Rad od ponoći')" />
              <input id="night_duration" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="night_duration" value={{$day->night_duration ? $day->night_duration->format('H:i') : '00:00'}} required />
            </div>

            <!-- pocetak -->
            <div class="mt-4">
              <x-label for="start" :value="__('Početak smjene')" />
              <input id="start" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="start" value={{$day->start->format('H:i')}} required />
            </div>

            <!-- duzina -->
            <div class="mt-4">
              <x-label for="duration" :value="__('Dužina rada')" />
              <input id="duration" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="duration" value={{$day->duration->format('H:i')}} required />
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

          <form method="POST" action="{{ route('days.store') }}">
            @csrf

            <!-- date -->
            <div class="mt-4">
              <x-label for="date" :value="__('Dan')" />
              <input id="date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="date" name="date" value="{{$day->date ? $day->date->format('Y-m-d') : "old('date')"}}" required autofocus />
            </div>

            <!-- bolovanje -->
            <div class="mt-4">
              <x-label for="sick" :value="__('Bolovanje')" />
              <x-input id="sick" class="block mt-1 w-full" type="checkbox" name="sick" :value="old('sick')" />
            </div>

            <!-- nocna -->
            <div class="mt-4">
              <x-label for="night_duration" :value="__('Rad od ponoći')" />
              <input id="night_duration" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="night_duration" value="old('night_duration')" />
            </div>

            <!-- pocetak -->
            <div class="mt-4">
              <x-label for="start" :value="__('Početak smjene')" />
              <input id="start" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="start" value="{{$day->start ? $day->start->format('H:i') : "old('start')"}}" required />
            </div>

            <!-- duzina -->
            <div class="mt-4">
              <x-label for="duration" :value="__('Dužina rada')" />
              <input id="duration" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="duration" value="old('duration')" required />
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
git commit -am "months holidays [laravel]"
```