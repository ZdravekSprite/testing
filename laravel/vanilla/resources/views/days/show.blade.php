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
          <h3>Day {{ $day->date->format('d.m.Y') }}!</h3>
          @if($day->sick)
          <p>Bio sam na bolovanju</p>
          @endif
          @if($day->go)
          <p>Bio sam na godišnjem</p>
          @endif
          @if(!($day->sick) && !($day->go))
          @if($day->night_duration->hour > 0)
          <p>od ponoći sam radio do {{ $day->night_duration->format('H:i') }}</p>
          @endif
          <p>smjena je započela u {{ $day->start->format('H:i') }}</p>
          <p>smjena je završila u {{ $day->duration->format('H:i') }}</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
