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
          @hasrole('superadmin')
          You're superadmin!
          @else
          You're not superadmin!
          @endhasrole
          @hasrole('admin')
          <a class="nav-link" href="{{ route('admin.users.index') }}">{{ __('Menage Users') }}</a>
          @endhasrole
          @if (Auth::id() == 1)
          <p>
            <a href="{{ route('migrate') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
              </svg>
              migrate
            </a>
          </p>
          <p>
            <a href="{{ route('rollback') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
              </svg>
              rollback
            </a>
          </p>
          @endif
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('lista') }}">
            @csrf
            @method('PUT')
            <!-- bruto -->
            <div class="mt-4">
              <x-label for="bruto" :value="__('Bruto')" />
              <input id="bruto" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="bruto" value="{{Auth::user()->bruto ? Auth::user()->bruto : old('bruto')?? 5300}}" min="4250" step="50" />
            </div>
            <!-- prijevoz -->
            <div class="mt-4">
              <x-label for="prijevoz" :value="__('Prijevoz')" />
              <input id="prijevoz" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="prijevoz" value="{{Auth::user()->prijevoz ? Auth::user()->prijevoz : old('prijevoz')?? 360}}" min="0" step="10" />
            </div>
            <!-- odbitak -->
            <div class="mt-4">
              <x-label for="odbitak" :value="__('Odbitak')" />
              <input id="odbitak" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="odbitak" value="{{Auth::user()->odbitak ? Auth::user()->odbitak : old('odbitak')?? 4000}}" min="4000" step="50" />
              <div class="ml-12 mt-2 text-gray-600 dark:text-gray-400 text-sm">
                <a href="https://www.porezna-uprava.hr/baza_znanja/Stranice/OsobniOdbitak.aspx" class="underline text-gray-900 dark:text-white">OSOBNI ODBITAK</a>
              </div>
            </div>
            <!-- prirez -->
            <div class="mt-4">
              <x-label for="prirez" :value="__('Prirez')" />
              <input id="prirez" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="prirez" value="{{Auth::user()->prirez ? Auth::user()->prirez/10 : old('prirez')?? 18}}" min="0" step="0.5" />
              <div class="ml-12 mt-2 text-gray-600 dark:text-gray-400 text-sm">
                <a href="https://www.porezna-uprava.hr/HR_porezni_sustav/Stranice/Popisi/Stope.aspx" class="underline text-gray-900 dark:text-white">PRIREZ</a>
              </div>
            </div>
            <!-- zaposlen -->
            <div class="mt-4">
              <x-label for="zaposlen" :value="__('Zaposlen od')" />
              <input id="zaposlen" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="date" name="zaposlen" value="{{Auth::user()->zaposlen ? Auth::user()->zaposlen : old('zaposlen')}}" />
              <div class="ml-12 mt-2 text-gray-600 dark:text-gray-400 text-sm">
                Da bi se mogao točno izračunati prvi mjesec rada ako se nije zaposlilo prvog u mjesecu.
              </div>
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
