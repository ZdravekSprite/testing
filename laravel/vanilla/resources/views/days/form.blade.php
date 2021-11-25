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
            <x-div>
              <x-label for="state" :value="__('Vrsta dana')" />
              <x-select onchange="changeFunc();" id="state" name="state" class="block mt-1 w-full">
                <option value=0{{$day->state == 0 ? ' selected' : ''}}>{{ __('Nisam radio') }}</option>
                <option value=1{{$day->state == 1 ? ' selected' : ''}}>{{ __('Radio normalno') }}</option>
                <option value=2{{$day->state == 2 ? ' selected' : ''}}>{{ __('Godišnji') }}</option>
                <option value=3{{$day->state == 3 ? ' selected' : ''}}>{{ __('Plaćeni dopust') }}</option>
                <option value=4{{$day->state == 4 ? ' selected' : ''}}>{{ __('Bolovanje') }}</option>
              </x-select>
              <x-p>Odabrati, da li se radilo ili ne? Bolovanje? Godišnji?</x-p>
              <x-p>Da li ste taj dan dobili plačeni dopust?</x-p>
            </x-div>

            <!-- start -->
            <x-div :hidden="$day->state != 1" id="start_div">
              <x-label for="start" :value="__('Početak smjene')" />
              <x-input id="start" class="block mt-1 w-full" type="time" name="start" value="{{$day->start ? $day->start->format('H:i') : old('start')?? '00:00'}}" required />
              <x-p>Vrijeme kada je započela smjena.</x-p>
            </x-div>

            <!-- end -->
            <x-div :hidden="$day->state != 1" id="end_div">
              <x-label for="end" :value="__('Kraj smjene')" />
              <x-input id="end" class="block mt-1 w-full" type="time" name="end" value="{{$day->end ? $day->end->format('H:i') : old('end')?? '00:00'}}" required />
              <x-p>Kada je smjena završila.</x-p>
            </x-div>
