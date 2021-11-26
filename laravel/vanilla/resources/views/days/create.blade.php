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

          <form method="POST" action="{{ route('day.store') }}">
            @csrf

            <!-- date -->
            <x-div>
              <x-label for="date" :value="__('Dan')" />
              <x-input id="date" class="block mt-1 w-full" value="{{$day->date ? $day->date->format('Y-m-d') : old('date')?? date('Y-m-d')}}" required />
              <input id="date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="date" name="date" value="{{$day->date ? $day->date->format('Y-m-d') : "old('date')"}}" required autofocus />
              <x-p>Dan za koji se određuju sati rada</x-p>
            </x-div>

            @include('days.form')

            <div class="flex items-center justify-end mt-4">
              <x-button class="ml-4">
                {{ __('Napravi') }}
              </x-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
