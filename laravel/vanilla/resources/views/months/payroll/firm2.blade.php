          <tbody>
            <tr>
              <td class="border p-2">
                <ul>
                  <li><b>I. PODACI O POSLODAVCU</b></li>
                  <li>1. Tvrtka/ Ime i prezime: {{ env('FIRM21') }}</li>
                  <li>2. Sjedište / Adresa: {{ env('FIRM22') }}</li>
                  <li>3. Osobni identifikacijski broj: {{ env('FIRM23') }}</li>
                  <li>4. IBAN broj računa {{ env('FIRM24') }} kod {{ env('FIRM25') }}</li>
                </ul>
              </td>
              <td class="border p-2" colspan="3">
                <ul>
                  <li><b>II. PODACI O RADNIKU/RADNICI</b></li>
                  <li>
                    1. Ime i prezime: <b>{{ Auth::user()->name }}</b>
                  </li>
                  <li>2. Adresa: ____</li>
                  <li>3. Osobni identifikacijski broj: ____</li>
                  <li>4. IBAN broj računa ____ kod ____</li>
                  <li>5. IBAN broj računa iz čl. 212. Ovršnog zakona ____ kod ____</li>
                </ul>
              </td>
            </tr>
            <tr>
              <td class="border p-2" colspan="4"><b>III. RAZDOBLJE NA KOJE SE PLAĆA ODNOSI:</b> GODINA {{ $data['III.godina'] }}, MJESEC
                {{ $data['III.mjesec'] }} DANI U MJESECU OD {{ $data['III.od'] }} DO {{ $data['III.do'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>1. OSTVARENI SATI PO VREMENU</b></td>
              <td class="w-1/8 border p-2 text-center"><b>sati rada/postotak</b></td>
              <td class="w-1/8 border p-2 text-right"><b>IZNOS</b></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.1. sati redovnog rada</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.1.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.1.kn'] }}</td>
            </tr>
            @if($data['1.2.kn'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.2 redovnog rada noću</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.2.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.2.kn'] }}</td>
            </tr>
            @endif
            @if($data['1.3.kn'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.3. sati redovnog rada u dane državnog praznika/ blagdana</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.3.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.3.kn'] }}</td>
            </tr>
            @endif
            @if($data['1.7.kn'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.7. sati redovnog rada nedeljom</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.7.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.7.kn'] }}</td>
            </tr>
            @endif
            @if($data['1.8.kn'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.8. sati redovnog rada nedjeljom + noću</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.8.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.8.kn'] }}</td>
            </tr>
            @endif
            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>2. SATI ZA KOJE SE OSTVARUJE PRAVO NA NAKNADU</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">2.2. sati privremene spriječenosti za rad zbog bolesti</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['2.2.h'] }}</td>
              <td class="w-1/8 border p-2 text-right" title="{{ $data['2.2.t'] }}">{{ $data['2.2.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>3. Propisani ili ugovoeni dodaci na plaću radnika i novčani iznosi po toj osnovi</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">Minuli rad</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['3.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['3.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA - BRUTTO PLAĆA<b></td>
              <td class="w-1/8 border p-2 text-center">{{ $data['4.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['4.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2">5. OSNOVICA ZA OBRAČUN DOPRINOSA</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"><b>{{ $data['5.kn'] }}<b></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2">6. Vrste i iznosi doprinosa za obvezna osiguranja koji se obustavljaju iz plaće</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">6.1. MIO</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['6.1.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['6.1.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">6.2. MIO II</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['6.2.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['6.2.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>UKUPNI DOPRINOS IZ PLAĆE</b></td>
              <td class="w-1/8 border p-2 text-center">{{ $data['6.h'] }}</td>
              <td class="w-1/8 border p-2 text-right"><b>{{ $data['6.kn'] }}</b></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>7. DOHODAK - PLAĆA PRIJE OPOREZIVANJA</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"><b>{{ $data['7.kn'] }}</b></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2">OBRAĆUN POREZA</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2">8. Osobni odbitak</td>
              <td class="w-1/8 border p-2 text-center">1.60 / {{ number_format($month->odbitak/100, 2, '.', '') }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['8.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2">9. Osnovica za oporezivanje</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['9.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">Porez po stopi od 20.00%</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['9.kn'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['10.20.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">Prirez na porez</td>
              <td class="w-1/8 border p-2 text-center">{{ number_format($month->prirez/100, 2, '.', ',') }} % / {{ $data['10.20.kn'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['10.prirez.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>10. Ukupno porez i prirez</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['10.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>11. PLAĆA NAKON OPOREZIVANJA</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['11.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>12. NEOPOREZIVE NAKNADE I OSTALE ISPLATE</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">PRIJEVOZ</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['12.a.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">!Nagrada</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['12.b.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">!Prehrana</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['12.c.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">!Prigodna</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['12.d.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>15. ZA ISPLATU</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['15.kn'] }}</td>
            </tr>
          </tbody>