            <div class="grid grid-cols-1 md:grid-cols-3">
              <!-- prekovremeni -->
              <x-div>
                <x-label for="prekovremeni" :value="__('Prekovremeni')" />
                <x-input id="prekovremeni" type="number" name="prekovremeni" value="{{$month->prekovremeni?? old('prekovremeni')?? 0}}" min="0" step="1" />
                <x-p>nikad nije sigurno koliko će prekovremenih platiti, pa se točan broj koliko su ih platitili za precizniji izračun može dodati</x-p>
              </x-div>
              @hasrole(env('FIRM1'))
              <!-- nocni -->
              <x-div>
                <x-label for="nocni" :value="__('Nocni sati')" />
                <x-input id="nocni" type="number" name="nocni" value="{{$month->nocni/10?? old('nocni')?? 0}}" min="0" step="0.5" />
                <x-p>5 min tu, 5 min tamo i može se skupiti i pola sata ili više, pa za precizniji izračun dodaje se točno koliko je na platnoj listi</x-p>
              </x-div>
              @endhasrole
              <!-- bolovanje -->
              <x-div>
                <x-label for="bolovanje" :value="__('Bolovanje')" />
                <x-input id="bolovanje" type="number" name="bolovanje" value="{{$month->bolovanje/100?? old('bolovanje')?? 0}}" min="0" step="0.01" />
                <x-p>Satnica za bolovanje je 70% od prosjeka prethodnih 6 mjeseci. Komplikacija za izračun, a i pitanje da li ima podataka od prošlih 6 mjeseci, pa je jednostavnije da se upiše.</x-p>
              </x-div>

              <!-- stimulacija -->
              <x-div>
                <x-label for="nagrada" :value="__('Stimulacija')" />
                <x-input id="nagrada" type="number" name="nagrada" value="{{$month->nagrada/100?? old('nagrada')?? 0}}" min="0" step="50" />
                <x-p>Nagradna stimulacija.</x-p>
              </x-div>

              <!-- regres -->
              <x-div>
                <x-label for="regres" :value="__('Regres')" />
                <x-input id="regres" type="number" name="regres" value="{{$month->regres/100?? old('regres')?? 0}}" min="0" step="100" />
              </x-div>
              <!-- bozicnica -->
              <x-div>
                <x-label for="bozicnica" :value="__('Bozicnica')" />
                <x-input id="bozicnica" type="number" name="bozicnica" value="{{$month->bozicnica/100?? old('bozicnica')?? 0}}" min="0" step="100" />
              </x-div>
              @hasrole(env('FIRM1'))
              <!-- prehrana -->
              <x-div>
                <x-label for="prehrana" :value="__('Topli obrok')" />
                <x-input id="prehrana" type="number" name="prehrana" value="{{$month->prehrana/100?? old('prehrana')?? 0}}" min="0" step="0.01" />
              </x-div>
              @endhasrole
              @hasrole(env('FIRM2'))
              <!-- prigodna -->
              <x-div>
                <x-label for="prigodna" :value="__('Prigodna')" />
                <x-input id="prigodna" type="number" name="prigodna" value="{{$month->prigodna/100?? old('prigodna')?? 0}}" min="0" step="0.01" />
              </x-div>
              @endhasrole
              <!-- stimulacija Bruto-->
              <x-div>
                <x-label for="stimulacija" :value="__('Stimulacija Bruto')" />
                <x-input id="stimulacija" type="number" name="stimulacija" value="{{$month->stimulacija ? $month->stimulacija/100 : old('stimulacija')?? 0}}" min="0" step="0.01" />
                <x-p>Ako se ima više od zakonom dozvoljenih prekovremenih, višak se isplačuje kao bruto stimulacija</x-p>
              </x-div>
            </div>
