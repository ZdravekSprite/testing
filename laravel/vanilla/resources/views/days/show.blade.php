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
          <p>{{ $day[0]->go ? 'bio sam' : 'nisam bio' }} na godišnjem</p>
          <p>od ponoći {{ $day[0]->night_duration->hour > 0 ? 'sam radio ' + $day[0]->night_duration->format('H:i') + 'sati' : 'nisam radio' }}</p>
          <p>smjena je započela u {{ $day[0]->start->format('H:i') }} sati</p>
          <p>smjena je završila u {{ $day[0]->duration->format('H:i') }} sati</p>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
