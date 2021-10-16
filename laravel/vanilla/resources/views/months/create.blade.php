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

            <!-- month -->
            <div class="mt-4">
              <x-label for="month" :value="__('Mjesec')" />
              <select id="month" name="month" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @for($m=1; $m <= 12; $m++)
                  <option value="{{$m}}">{{$m}}</option>
                @endfor
              </select>
            </div>

            <!-- year -->
            <div class="mt-4">
              <x-label for="year" :value="__('Godina')" />
              <select id="year" name="year" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @for($y=2020; $y <= 2022; $y++)
                  <option value="{{$y}}">{{$y}}</option>
                @endfor
              </select>
            </div>

            <!-- bruto -->
            <div class="mt-4">
              <x-label for="bruto" :value="__('Bruto')" />
              <input id="bruto" type="number" name="bruto" value="{{$month->bruto ? $month->bruto/100 : old('bruto')?? 5300}}" min="4250" step="50" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"/>
            </div>

            <!-- prijevoz -->
            <div class="mt-4">
              <x-label for="prijevoz" :value="__('Prijevoz')" />
              <input id="prijevoz" type="number" name="prijevoz" value="{{$month->prijevoz ? $month->prijevoz/100 : old('prijevoz')?? 360}}" min="0" step="10" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"/>
            </div>

            <!-- odbitak -->
            <div class="mt-4">
              <x-label for="odbitak" :value="__('Odbitak')" />
              <input id="odbitak" type="number" name="odbitak" value="{{$month->odbitak ? $month->odbitak/100 : old('odbitak')?? 4000}}" min="4000" step="50" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"/>
            </div>

            <!-- prirez -->
            <div class="mt-4">
              <x-label for="prirez" :value="__('Prirez')" />
              <input id="prirez" type="number" name="prirez" value="{{$month->prirez ? $month->prirez/100 : old('prirez')?? 0}}" min="0" step="0.1" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"/>
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
