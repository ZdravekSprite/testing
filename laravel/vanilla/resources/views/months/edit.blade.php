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

            <!-- bruto -->
            <div class="mt-4">
              <x-label for="bruto" :value="__('Bruto')" />
              <input id="bruto" type="number" name="bruto" value="{{$month->bruto ? $month->bruto/100 : old('bruto')?? 5300}}" min="4250" step="50" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <!-- prijevoz -->
            <div class="mt-4">
              <x-label for="prijevoz" :value="__('Prijevoz')" />
              <input id="prijevoz" type="number" name="prijevoz" value="{{$month->prijevoz ? $month->prijevoz/100 : old('prijevoz')?? 300}}" min="0" step="10" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <!-- odbitak -->
            <div class="mt-4">
              <x-label for="odbitak" :value="__('Odbitak')" />
              <input id="odbitak" type="number" name="odbitak" value="{{$month->odbitak ? $month->odbitak/100 : old('odbitak')?? 4000}}" min="4000" step="50" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <!-- prirez -->
            <div class="mt-4">
              <x-label for="prirez" :value="__('Prirez')" />
              <input id="prirez" type="number" name="prirez" value="{{$month->prirez ? $month->prirez/100 : old('prirez')?? 0}}" min="0" step="0.1" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <!-- prekovremeni -->
            <div class="mt-4">
              <x-label for="prekovremeni" :value="__('Prekovremeni')" />
              <input id="prekovremeni" type="number" name="prekovremeni" value="{{$month->prekovremeni?? old('prekovremeni')?? 0}}" min="0" step="1" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <!-- nocni -->
            <div class="mt-4">
              <x-label for="nocni" :value="__('Nocni')" />
              <input id="nocni" type="number" name="nocni" value="{{$month->nocni/10?? old('nocni')?? 0}}" min="0" step="0.5" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <!-- stimulacija -->
            <div class="mt-4">
              <x-label for="nagrada" :value="__('Stimulacija')" />
              <input id="nagrada" type="number" name="nagrada" value="{{$month->nagrada/100?? old('nagrada')?? 0}}" min="0" step="100" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <!-- regres -->
            <div class="mt-4">
              <x-label for="regres" :value="__('Regres')" />
              <input id="regres" type="number" name="regres" value="{{$month->regres/100?? old('regres')?? 0}}" min="0" step="100" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <!-- stimulacija Bruto-->
            <div class="mt-4">
              <x-label for="stimulacija" :value="__('Stimulacija Bruto')" />
              <input id="stimulacija" type="number" name="stimulacija" value="{{$month->stimulacija ? $month->stimulacija/100 : old('stimulacija')?? 0}}" min="0" step="0.01" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <!-- sindikat -->
            <div class="mt-4">
              <x-label for="sindikat" :value="__('Sindikat')" />
              <input id="sindikat" type="checkbox" name="sindikat" {{$month->sindikat ? 'checked' : ''}} class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
            </div>

            <!-- kredit -->
            <div class="mt-4">
              <x-label for="kredit" :value="__('Kredit')" />
              <input id="kredit" type="number" name="kredit" value="{{$month->kredit/100?? old('kredit')?? 0}}" min="0" step="0.01" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
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
