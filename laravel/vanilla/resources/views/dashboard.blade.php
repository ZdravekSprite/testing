<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          You're logged in!
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('bruto') }}">
            @csrf
            @method('PUT')
            <!-- bruto -->
            <div class="mt-4">
              <x-label for="bruto" :value="__('Bruto')" />
              <input id="bruto" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="bruto" value="{{Auth::user()->bruto ? Auth::user()->bruto : old('bruto')?? 5300}}" min="4250" step="50" />
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
