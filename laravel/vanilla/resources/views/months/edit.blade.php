<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ $month->slug() }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          Edit {{ $month->slug() }}!
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('months.update', ['month' => $month->slug()])}}">
            @csrf
            @method('PUT')
            <!-- month -->
            <div class="mt-4">
              <x-label for="month" :value="__('Mjesec')" />
              <input id="month" type="number" name="month" value="{{$month->month}}" class="hidden" required />
              {{$month->slug()}}
            </div>

            @include('months.form1')

            @include('months.form2')

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
