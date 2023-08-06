<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Novi dan') }}
    </h2>
  </x-slot>

  <div class="py-12 space-y-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

          <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
              {{ __('Create new!') }}
            </h2>
          </header>

          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('day.store') }}">
            @csrf

            <!-- date -->
            <x-div>
              <x-label for="date" :value="__('Dan')" />
              <x-input id="date" type="date" name="date" :value="$day->date ? $day->date->format('Y-m-d') : old('date')" required autofocus />
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