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
          @hasrole('admin')
          <p class="flex justify-between">
            <a class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex" href="{{ route('migrate') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
              </svg>
              <span>migrate</span>
            </a>
          </p>
          <p class="flex justify-between">
            <a class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex" href="{{ route('rollback') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
              </svg>
              <span>rollback</span>
            </a>
          </p>
          @endhasrole
          @hasrole('superadmin')
          <p>You're super admin!</p>
          <div id="app">
            <example-component></example-component>
          </div>
          @else
          <p>You're logged in!</p>
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('settings.update')}}">
            @csrf
            <div class="mt-4">
              <x-label for="start1" :value="__('Početak 1. smjene')" />
              <input id="start1" type="time" name="start1" value="{{ $settings ? $settings->start1->format('H:i') : old('start1')?? '06:00'}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
              <p>Vrijeme kada započinje 1. smjena.</p>
              <x-label for="end1" :value="__('Kraj 1. smjene')" />
              <input id="end1" type="time" name="end1" value="{{ $settings ? $settings->end1->format('H:i') : old('end1')?? '14:00'}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
              <p>Vrijeme kada završava 1. smjena.</p>
            </div>
            <div class="mt-4">
              <x-label for="start2" :value="__('Početak 2. smjene')" />
              <input id="start2" type="time" name="start2" value="{{ $settings ? $settings->start2->format('H:i') : old('start2')?? '14:00'}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
              <p>Vrijeme kada započinje 2. smjena.</p>
              <x-label for="end2" :value="__('Kraj 2. smjene')" />
              <input id="end2" type="time" name="end2" value="{{ $settings ? $settings->end2->format('H:i') : old('end2')?? '22:00'}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
              <p>Vrijeme kada završava 2. smjena.</p>
            </div>
            <div class="mt-4">
              <x-label for="start3" :value="__('Početak 3. smjene')" />
              <input id="start3" type="time" name="start3" value="{{ $settings ? $settings->start3->format('H:i') : old('start3')?? '22:00'}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
              <p>Vrijeme kada započinje 3. smjena.</p>
              <x-label for="end2" :value="__('Kraj 3. smjene')" />
              <input id="end3" type="time" name="end3" value="{{ $settings ? $settings->end3->format('H:i') : old('end3')?? '06:00'}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
              <p>Vrijeme kada završava 3. smjena.</p>
            </div>
            <!-- zaposlen -->
            <div class="mt-4">
              <x-label for="zaposlen" :value="__('Zaposlen od')" />
              <input id="zaposlen" type="date" name="zaposlen" value="{{ ($settings && $settings->zaposlen) ? $settings->zaposlen->format('Y-m-d') : old('zaposlen')}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
              <div class="ml-12 mt-2 text-gray-600 dark:text-gray-400 text-sm">
                Da bi se mogao točno izračunati prvi mjesec rada ako se nije zaposlilo prvog u mjesecu.
              </div>
            </div>
            @hasrole('binance')
            <div class="mt-4">
              <x-label for="bkey" :value="__('BINANCE_API_KEY')" />
              <input id="bkey" type="text" name="bkey" value="{{ $settings ? $settings->BINANCE_API_KEY : old('bkey')?? null}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>
            <div class="mt-4">
              <x-label for="bsecret" :value="__('BINANCE_API_SECRET')" />
              <input id="bsecret" type="text" name="bsecret" value="{{ $settings ? $settings->BINANCE_API_SECRET : old('bsecret')?? null}}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>
            @endhasrole

            <div class="flex items-center justify-end mt-4">
              <x-button class="ml-4">
                {{ __('Spremi') }}
              </x-button>
            </div>
          </form>
          @endhasrole
        </div>
      </div>
    </div>
  </div>
</x-app-layout>