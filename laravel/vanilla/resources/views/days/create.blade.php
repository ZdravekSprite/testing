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
