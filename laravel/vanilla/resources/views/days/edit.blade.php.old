<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Uredi dan') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          Edit {{$day->date->format('d.m.Y')}} day!
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('days.update' , ['day' => $day->date->format('d.m.Y')]) }}">
            @csrf
            @method('PUT')

            <!-- date -->
            <input id="date" class="hidden" type="date" name="date" value={{$day->date->format('Y-m-d')}} required autofocus />

            <script type="text/javascript">
              function ShowHideDiv(chkId) {
                var dvEl = document.getElementById("time");
                dvEl.style.display = chkId.checked ? "none" : "block";
                document.getElementById("night_duration").value = "00:00";
                document.getElementById("start").value = "00:00";
                document.getElementById("duration").value = "00:00";
              }

            </script>
            <!-- bolovanje -->
            <div class="mt-4">
              <x-label for="sick" :value="__('Bolovanje')" />
              <input onclick="ShowHideDiv(this)" id="sick" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" name="sick" {{$day->sick ? 'checked' : ''}} />
            </div>

            <!-- GO -->
            <div class="mt-4">
              <x-label for="go" :value="__('Godišnji')" />
              <input onclick="ShowHideDiv(this)" id="go" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" name="go" {{$day->go ? 'checked' : ''}} />
            </div>

            <!-- dopust -->
            <div class="mt-4">
              <x-label for="dopust" :value="__('Plačeni dopust')" />
              <input onclick="ShowHideDiv(this)" id="dopust" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" name="dopust" {{$day->dopust ? 'checked' : ''}} />
            </div>

            <div id="time">
              <!-- nocna -->
              <div class="mt-4">
                <x-label for="night_duration" :value="__('Kraj noćne prijašnji dan')" />
                <input id="night_duration" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="night_duration" value={{$day->night_duration ? $day->night_duration->format('H:i') : '00:00'}} required />
              </div>

              <!-- pocetak -->
              <div class="mt-4">
                <x-label for="start" :value="__('Početak smjene')" />
                <input id="start" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="start" value={{$day->start->format('H:i')}} required />
              </div>

              <!-- duzina -->
              <div class="mt-4">
                <x-label for="duration" :value="__('Kraj smjene')" />
                <input id="duration" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="duration" value={{$day->duration->format('H:i')}} required />
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
