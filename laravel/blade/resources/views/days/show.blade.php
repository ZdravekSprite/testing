<x-app-layout>
  <x-slot name="header">
  <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Dan') }}
      {{ $day->date->format('d.m.Y') }}
      <a class="float-right" href="{{ route('months.show', ['month' => $day->date->format('m.Y')]) }}" title="{{$day->date->format('m.Y')}}">
      {{ __('Mjesec') }}
      {{ $day->date->format('m.Y') }}
      </a>
    </h2>
  </x-slot>

  <div class="py-12 space-y-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
              Day {{ $day->date->format('d.m.Y') }}!
              </h2>
          </header>

          @switch($day->state)
          @case(0)
          <x-p>Nisam radio/la</x-p>
          @break

          @case(1)
          @if($day->night && $day->night->hour > 0)
          <x-p>od ponoći sam radio/la do {{ $day->night->format('H:i') }}</x-p>
          @endif
          <x-p>smjena je započela u {{ $day->start->format('H:i') }}</x-p>
          <x-p>smjena je završila u {{ $day->end->format('H:i') }}</x-p>
          @break

          @case(2)
          <x-p>Bio/la sam na godišnjem</x-p>
          @break

          @case(3)
          <x-p>Bio/la sam na plaćenom dopustu</x-p>
          @break

          @case(4)
          <x-p>Bio/la sam na bolovanju</x-p>
          @break
          @default
          <p>Ne definirano</p>
          @endswitch
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
