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

          @switch($day->state)
          @case(0)
          <p>Nisam radio/la</p>
          @break

          @case(1)
          @if($day->night->hour > 0)
          <p>od ponoći sam radio/la do {{ $day->night->format('H:i') }}</p>
          @endif
          <p>smjena je započela u {{ $day->start->format('H:i') }}</p>
          <p>smjena je završila u {{ $day->end->format('H:i') }}</p>
          @break

          @case(2)
          <p>Bio/la sam na godišnjem</p>
          @break

          @case(3)
          <p>Bio/la sam na plaćenom dopustu</p>
          @break

          @case(4)
          <p>Bio/la sam na bolovanju</p>
          @break
          @default
          <p>Ne definirano</p>
          @endswitch
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
