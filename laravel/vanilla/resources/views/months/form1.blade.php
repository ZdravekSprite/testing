            <div class="grid grid-cols-1 md:grid-cols-2">
              <!-- bruto -->
              <x-div>
                <x-label for="bruto" :value="__('Bruto')" />
                <x-input id="bruto" type="number" name="bruto" value="{{$month->bruto ? $month->bruto/100 : old('bruto')?? 5300}}" min="4250" step="50" />
              </x-div>

              <!-- prijevoz -->
              <x-div>
                <x-label for="prijevoz" :value="__('Prijevoz')" />
                <x-input id="prijevoz" type="number" name="prijevoz" value="{{$month->prijevoz ? $month->prijevoz/100 : old('prijevoz')?? 300}}" min="0" step="10" />
              </x-div>
              @hasrole(env('FIRM2'))
              <!-- prehrana -->
              <x-div>
                <x-label for="prehrana" :value="__('Prehrana')" />
                <x-input id="prehrana" type="number" name="prehrana" value="{{$month->prehrana/100?? old('prehrana')?? 0}}" min="0" step="0.01" />
              </x-div>
              <!-- minuli -->
              <x-div>
                <x-label for="minuli" :value="__('Minuli rad')" />
                <x-input id="minuli" type="number" name="minuli" value="{{$month->minuli/10?? old('minuli')?? 0}}" min="0" step="0.1" />
              </x-div>
              @endhasrole
              <!-- odbitak -->
              <x-div>
                <x-label for="odbitak" :value="__('Odbitak')" />
                <x-input id="odbitak" type="number" name="odbitak" value="{{$month->odbitak ? $month->odbitak/100 : old('odbitak')?? 4000}}" min="4000" step="50" />
              </x-div>

              <!-- prirez -->
              <x-div>
                <x-label for="prirez" :value="__('Prirez')" />
                <x-input id="prirez" type="number" name="prirez" value="{{$month->prirez ? $month->prirez/100 : old('prirez')?? 0}}" min="0" step="0.1" />
              </x-div>
              @hasrole(env('FIRM1'))
              <!-- sindikat -->
              <x-div>
                <x-label for="sindikat" :value="__('Sindikat')" />
                <div class="flex justify-center">
                  <x-input id="sindikat" :width="'1/2'" type="checkbox" name="sindikat" class="h-6 my-3" :checked="$month->sindikat ? 'checked' : null" />
                </div>
              </x-div>

              <!-- kredit -->
              <x-div>
                <x-label for="kredit" :value="__('Kredit')" />
                <x-input id="kredit" type="number" name="kredit" value="{{$month->kredit/100?? old('kredit')?? 0}}" min="0" step="0.01" />
              </x-div>
              @endhasrole
            </div>
