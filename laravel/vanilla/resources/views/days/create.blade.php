<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Novi dan') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          Create new!
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('days.store') }}">
            @csrf

            <!-- date -->
            <div class="mt-4">
              <x-label for="date" :value="__('Dan')" />
              <input id="date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="date" name="date" value="{{$day->date ? $day->date->format('Y-m-d') : "old('date')"}}" required autofocus />
              <p>Dan za koji se određuju sati rada</p>
            </div>

            <script type="text/javascript">
              function ShowHideDiv(chkId) {
                //var dvEl = document.getElementById("time");
                //dvEl.style.display = chkId.checked ? "none" : "block";
                document.getElementById("night_duration").value = "00:00";
                document.getElementById("start").value = "00:00";
                document.getElementById("duration").value = "00:00";
              }

            </script>
            <!-- bolovanje -->
            <div class="mt-4">
              <x-label for="sick" :value="__('Bolovanje')" />
              <input onclick="ShowHideDiv(this)" id="sick" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" name="sick" />
              <p>Da li ste taj dan bili na bolovanju? Ako je bolovanje onda bi ostale vrijednosti trebale biti 00:00</p>
            </div>

            <!-- GO -->
            <div class="mt-4">
              <x-label for="go" :value="__('Godišnji')" />
              <input onclick="ShowHideDiv(this)" id="go" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="checkbox" name="go" />
              <p>Da li ste taj dan bili na godišnjem? Ako je godišnji onda bi ostale vrijednosti trebale biti 00:00</p>
              <p>Kako nisam još bio na GO ne znam kak se računa ali pretpostavljam da kao i za bolovanje, ali ako netko zna točno slobodno javi na <a href="mailto:zdravek.sprite@gmail.com">mail zdravek.sprite@gmail.com</a></p>
            </div>

            <!-- nocna -->
            <div class="mt-4">
              <x-label for="night_duration" :value="__('Kraj noćne prijašnji dan')" />
              <input id="night_duration" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="night_duration" value="{{old('night_duration')?? '00:00'}}" />
              <p>Još nisam radio noćnu pa neznam kak se raćuna, ali pošto se započinje smjena u jednom danu a završava u drugom dodao sam da se može odrediti koliko se radilo od ponoći</p>
              <p>Još nisam dodao da se automatski ako kraj smjene prelazi na drugi dan podjele sati na 2 dana, ali ako nekome treba slobodno neka javi na <a href="mailto:zdravek.sprite@gmail.com">mail zdravek.sprite@gmail.com</a></p>
            </div>

            <!-- pocetak -->
            <div class="mt-4">
              <x-label for="start" :value="__('Početak smjene')" />
              <input id="start" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="start" value="{{$day->start ? $day->start->format('H:i') : old('start')?? '06:00'}}" required />
              <p>Vrijeme kada započinje smjena. Za default vrijednost sam uzeo 06:00, mada uglavnom radim popodne ali kak bi se reklo prva smjena je prva :)</p>
            </div>

            <!-- duzina -->
            <div class="mt-4">
              <x-label for="duration" :value="__('Kraj smjene')" />
              <input id="duration" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="time" name="duration" value="{{$day->duration ? $day->duration->format('H:i') : old('duration')?? '14:00'}}" required />
              <p>Kada je smjena završila, ako je noćna onda bi trebalo biti 24:00 i dodati ostatak u drugi dan jer automatsko prebacivanje nočnih sati na drugi dan još nisam složio. Kako nisam još radio noćne i nije da planiram, mada ćujem da je dosta veća lova, s tim djelom problema se nisam dodatno pozabavio pa preostaje ručno narihtavanje.</p>
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
