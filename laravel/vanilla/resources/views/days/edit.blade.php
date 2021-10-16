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

          <form method="POST" action="{{ route('day.update', ['date' => $day->date->format('d.m.Y')]) }}">
            @csrf

            <!-- date -->
            <input id="date" class="hidden" type="date" name="date" value={{$day->date->format('Y-m-d')}} required autofocus />

            <script type="text/javascript">
              function changeFunc() {
                var selectBox = document.getElementById("state");
                var selectedValue = selectBox.options[selectBox.selectedIndex].value;
                if (selectedValue == 1) {
                  document.getElementById("start_div").style.display = 'block';
                  document.getElementById("end_div").style.display = 'block';
                  document.getElementById("start").value = "06:00";
                  document.getElementById("end").value = "14:00";
                } else {
                  document.getElementById("start_div").style.display = 'none';
                  document.getElementById("end_div").style.display = 'none';
                }
              }

            </script>
            <!-- state -->
            <div class="mt-4">
              <x-label for="state" :value="__('Vrsta dana')" />
              <select onchange="changeFunc();" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="state" name="state">
                <option value=0{{$day->state == 0 ? ' selected' : ''}}>{{ __('Nisam radio') }}</option>
                <option value=1{{$day->state == 1 ? ' selected' : ''}}>{{ __('Radio normalno') }}</option>
                <option value=2{{$day->state == 2 ? ' selected' : ''}}>{{ __('Godišnji') }}</option>
                <option value=3{{$day->state == 3 ? ' selected' : ''}}>{{ __('Plaćeni dopust') }}</option>
                <option value=4{{$day->state == 4 ? ' selected' : ''}}>{{ __('Bolovanje') }}</option>
              </select>
              <p>Radio ili ne radio? Bolovanje? Godišnji?</p>
              <p>Da li ste taj dan dobili plačeni dopust?</p>
              <p>Ja sam za smrt u obitelji dobio 2 plačena dana <a href="mailto:zdravek.sprite@gmail.com">mail zdravek.sprite@gmail.com</a></p>
            </div>

            <!-- start -->
            <div class="mt-4{{$day->state == 1 ? '' : ' hidden'}}" id="start_div">
              <x-label for="start" :value="__('Početak smjene')" />
              <input id="start" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="start" value="{{$day->start ? $day->start->format('H:i') : old('start')?? '00:00'}}" required />
              <p>Vrijeme kada započinje smjena. Za default vrijednost sam uzeo 06:00, mada uglavnom radim popodne ali kak bi se reklo prva smjena je prva :)</p>
            </div>

            <!-- end -->
            <div class="mt-4{{$day->state == 1 ? '' : ' hidden'}}" id="end_div">
              <x-label for="end" :value="__('Kraj smjene')" />
              <input id="end" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="end" value="{{$day->end ? $day->end->format('H:i') : old('end')?? '00:00'}}" required />
              <p>Kada je smjena završila. Za nočnu nisam još narihtao da računa da smjena završava drugi dan pa vjerojatno računica neće valjati.</p>
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
