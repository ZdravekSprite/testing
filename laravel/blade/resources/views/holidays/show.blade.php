<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Praznik') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <h3>Holiday {{ $holiday->name }} {{ $holiday->date->format('Y') }}!</h3>
          <p>{{ $holiday->date->format('d.m.Y') }}</p>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>
