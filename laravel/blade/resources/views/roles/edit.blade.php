<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Uredi ulogu') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
              {{ __('Edit') }} {{$role->name}} {{ __('ulogu!') }}
            </h2>
          </header>
          
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf
            @method('PUT')
            <!-- name -->
            <input id="name" class="hidden" type="text" name="name" value={{$role->name}} required />

            @include('roles.form')

            <div class="flex items-center justify-end mt-4">
              <x-button class="ml-4">
                {{ __('Uredi') }}
              </x-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>