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

          <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf

            <!-- name -->
            <div class="mt-4">
              <x-label for="name" :value="__('Ime')" />
              <x-input id="name" type="text" name="name" value="{{ old('name') ?? ''}}" required autofocus />
            </div>

            @include('admin.roles.form')

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