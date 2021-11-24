            <!-- prekovremeni -->
            <div class="mt-4">
              <x-label for="prekovremeni" :value="__('Prekovremeni')" />
              <x-input id="prekovremeni" type="number" name="prekovremeni" value="{{$month->prekovremeni?? old('prekovremeni')?? 0}}" min="0" step="1" class="block mt-1 w-full" />
            </div>

            <!-- nocni -->
            <div class="mt-4">
              <x-label for="nocni" :value="__('Nocni')" />
              <x-input id="nocni" type="number" name="nocni" value="{{$month->nocni/10?? old('nocni')?? 0}}" min="0" step="0.5" class="block mt-1 w-full" />
            </div>

            <!-- bolovanje -->
            <div class="mt-4">
              <x-label for="bolovanje" :value="__('Bolovanje')" />
              <x-input id="bolovanje" type="number" name="bolovanje" value="{{$month->bolovanje/100?? old('bolovanje')?? 0}}" min="0" step="0.01" class="block mt-1 w-full" />
              <p>Satnica za bolovanje je 70% od prosjeka prethodnih 6 mjeseci. Komplikacija za izračun, a i pitanje da li ima podataka od prošlih 6 mjeseci, pa je jednostavnije da se upiše.</p>
            </div>

            <!-- stimulacija -->
            <div class="mt-4">
              <x-label for="nagrada" :value="__('Stimulacija')" />
              <x-input id="nagrada" type="number" name="nagrada" value="{{$month->nagrada/100?? old('nagrada')?? 0}}" min="0" step="50" class="block mt-1 w-full" />
            </div>

            <!-- regres -->
            <div class="mt-4">
              <x-label for="regres" :value="__('Regres')" />
              <x-input id="regres" type="number" name="regres" value="{{$month->regres/100?? old('regres')?? 0}}" min="0" step="100" class="block mt-1 w-full" />
            </div>

            <!-- stimulacija Bruto-->
            <div class="mt-4">
              <x-label for="stimulacija" :value="__('Stimulacija Bruto')" />
              <x-input id="stimulacija" type="number" name="stimulacija" value="{{$month->stimulacija ? $month->stimulacija/100 : old('stimulacija')?? 0}}" min="0" step="0.01" class="block mt-1 w-full" />
            </div>