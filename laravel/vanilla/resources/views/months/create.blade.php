<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Novi mjesec') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          Create new!
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('months.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2">
              <!-- month -->
              <div class="mt-4">
                <x-label for="month" :value="__('Mjesec')" />
                <x-select id="month" name="month">
                  @for($m=1; $m <= 12; $m++)
                    <option value="{{$m}}">{{$m}}</option>
                  @endfor
                </x-select>
              </div>

              <!-- year -->
              <div class="mt-4">
                <x-label for="year" :value="__('Godina')" />
                <x-select id="year" name="year">
                  @for($y=2020; $y <= 2022; $y++)
                    <option value="{{$y}}">{{$y}}</option>
                  @endfor
                </x-select>
              </div>
            </div>

            @include('months.form1')

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
