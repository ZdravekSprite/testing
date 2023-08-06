<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12 space-y-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @hasrole('admin')
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <div class="max-w-xl">
            <p class="flex justify-between text-gray-900 dark:text-gray-100">
              <a class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex" href="{{ route('migrate') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
                </svg>
                <span>migrate</span>
              </a>
            </p>
            <p class="flex justify-between text-gray-900 dark:text-gray-100">
              <a class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex" href="{{ route('rollback') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
                </svg>
                <span>rollback</span>
              </a>
            </p>
            <p class="flex justify-between text-gray-900 dark:text-gray-100">
              <a class="hidden space-x-2 sm:-my-px sm:ml-10 sm:flex" href="{{ route('reset') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-bootstrap-reboot" viewBox="0 0 16 16">
                  <path d="M1.161 8a6.84 6.84 0 1 0 6.842-6.84.58.58 0 1 1 0-1.16 8 8 0 1 1-6.556 3.412l-.663-.577a.58.58 0 0 1 .227-.997l2.52-.69a.58.58 0 0 1 .728.633l-.332 2.592a.58.58 0 0 1-.956.364l-.643-.56A6.812 6.812 0 0 0 1.16 8z" />
                  <path d="M6.641 11.671V8.843h1.57l1.498 2.828h1.314L9.377 8.665c.897-.3 1.427-1.106 1.427-2.1 0-1.37-.943-2.246-2.456-2.246H5.5v7.352h1.141zm0-3.75V5.277h1.57c.881 0 1.416.499 1.416 1.32 0 .84-.504 1.324-1.386 1.324h-1.6z" />
                </svg>
                <span>reset</span>
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
    @endhasrole
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <div class="max-w-xl">
            @hasrole('superadmin')
            <x-p>You're super admin!</x-p>
            <x-p><a class="hidden sm:-my-px sm:ml-10 sm:flex" href="{{ route('admin.export.days') }}">export days</a></x-p>
            <x-p><a class="hidden sm:-my-px sm:ml-10 sm:flex" href="{{ route('admin.export.draws') }}">export draws</a></x-p>
            <x-p><a class="hidden sm:-my-px sm:ml-10 sm:flex" href="{{ route('admin.export.holidays') }}">export holidays</a></x-p>
            <x-p><a class="hidden sm:-my-px sm:ml-10 sm:flex" href="{{ route('admin.export.months') }}">export months</a></x-p>
            @else
            <x-p>You're logged in!</x-p>
            @endhasrole
          </div>
        </div>
      </div>
    </div>
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