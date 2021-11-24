            <div class="grid grid-cols-1 md:grid-cols-2">
            <!-- bruto -->
            <div class="mt-4">
              <x-label for="bruto" :value="__('Bruto')" />
              <x-input id="bruto" type="number" name="bruto" value="{{$month->bruto ? $month->bruto/100 : old('bruto')?? 5300}}" min="4250" step="50" class="block mt-1 w-full" />
            </div>

            <!-- prijevoz -->
            <div class="mt-4">
              <x-label for="prijevoz" :value="__('Prijevoz')" />
              <x-input id="prijevoz" type="number" name="prijevoz" value="{{$month->prijevoz ? $month->prijevoz/100 : old('prijevoz')?? 300}}" min="0" step="10" class="block mt-1 w-full" />
            </div>

            <!-- odbitak -->
            <div class="mt-4">
              <x-label for="odbitak" :value="__('Odbitak')" />
              <x-input id="odbitak" type="number" name="odbitak" value="{{$month->odbitak ? $month->odbitak/100 : old('odbitak')?? 4000}}" min="4000" step="50" class="block mt-1 w-full" />
            </div>

            <!-- prirez -->
            <div class="mt-4">
              <x-label for="prirez" :value="__('Prirez')" />
              <x-input id="prirez" type="number" name="prirez" value="{{$month->prirez ? $month->prirez/100 : old('prirez')?? 0}}" min="0" step="0.1" class="block mt-1 w-full" />
            </div>

            <!-- sindikat -->
            <div class="mt-4">
              <x-label for="sindikat" :value="__('Sindikat')" />
              <x-input id="sindikat" type="checkbox" name="sindikat" class="w-1/2 sd:w-full h-6 my-3" :checked="$month->sindikat ? 'checked' : null" />
            </div>

            <!-- kredit -->
            <div class="mt-4">
              <x-label for="kredit" :value="__('Kredit')" />
              <x-input id="kredit" type="number" name="kredit" value="{{$month->kredit/100?? old('kredit')?? 0}}" min="0" step="0.01" class="block mt-1 w-full" />
            </div>
            </div>