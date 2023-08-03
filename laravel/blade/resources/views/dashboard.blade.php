<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <div class="max-w-xl">
            <form method="post" action="{{ route('settings.update') }}" class="mt-6 space-y-6">
              @csrf
              @method('put')
              <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                  {{ __('1. smjena') }}
                </h2>
              </header>
              <div>
                <x-label for="start1" :value="__('Početak')" />
                <input id="start1" type="time" name="start1" value="{{ $settings ? $settings->start1->format('H:i') : old('start1')?? '06:00'}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                <x-p>Vrijeme kada započinje 1. smjena.</x-p>
                <x-label for="end1" :value="__('Kraj')" />
                <input id="end1" type="time" name="end1" value="{{ $settings ? $settings->end1->format('H:i') : old('end1')?? '14:00'}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                <x-p>Vrijeme kada završava 1. smjena.</x-p>
              </div>
              <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                  {{ __('2. smjena') }}
                </h2>
              </header>
              <div>
                <x-label for="start2" :value="__('Početak')" />
                <input id="start2" type="time" name="start2" value="{{ $settings ? $settings->start2->format('H:i') : old('start2')?? '14:00'}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                <x-p>Vrijeme kada započinje 2. smjena.</x-p>
                <x-label for="end2" :value="__('Kraj')" />
                <input id="end2" type="time" name="end2" value="{{ $settings ? $settings->end2->format('H:i') : old('end2')?? '22:00'}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                <x-p>Vrijeme kada završava 2. smjena.</x-p>
              </div>
              <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                  {{ __('3. smjena') }}
                </h2>
              </header>
              <div>
                <x-label for="start3" :value="__('Početak')" />
                <input id="start3" type="time" name="start3" value="{{ $settings ? $settings->start3->format('H:i') : old('start3')?? '22:00'}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                <x-p>Vrijeme kada započinje 3. smjena.</x-p>
                <x-label for="end2" :value="__('Kraj')" />
                <input id="end3" type="time" name="end3" value="{{ $settings ? $settings->end3->format('H:i') : old('end3')?? '06:00'}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                <x-p>Vrijeme kada završava 3. smjena.</x-p>
              </div>
              <!-- zaposlen -->
              <div class="my-4">
                <x-label for="zaposlen" :value="__('Zaposlen od')" />
                <input id="zaposlen" type="date" name="zaposlen" value="{{ ($settings && $settings->zaposlen) ? $settings->zaposlen->format('Y-m-d') : old('zaposlen')}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                <x-p>Da bi se mogao točno izračunati prvi mjesec rada ako se nije zaposlilo prvog u mjesecu.</x-p>
              </div>
              <!-- BINANCE -->
              <header>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                  {{ __('BINANCE') }}
                </h2>
              </header>
              <div>
                <x-label for="bkey" :value="__('BINANCE_API_KEY')" />
                <x-input id="bkey" type="text" name="bkey" value="{{ $settings ? $settings->BINANCE_API_KEY : old('bkey')?? null}}" />
              </div>
              <div>
                <x-label for="bsecret" :value="__('BINANCE_API_SECRET')" />
                <x-input id="bsecret" type="text" name="bsecret" value="{{ $settings ? $settings->BINANCE_API_SECRET : old('bsecret')?? null}}" />
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
  </div>
</x-app-layout>