          <tbody>
            <tr>
              <td class="border p-2">
                <ul>
                  <li><b>I. PODACI O POSLODAVCU</b></li>
                  <li>1. Tvrtka/ Ime i prezime: {{ env('FIRM11') }}</li>
                  <li>2. Sjedište / Adresa: {{ env('FIRM12') }}</li>
                  <li>3. Osobni identifikacijski broj: {{ env('FIRM13') }}</li>
                  <li>4. IBAN broj računa {{ env('FIRM14') }} kod {{ env('FIRM15') }}</li>
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
              <td class="w-3/4 border p-2" colspan="2"><b>1. OPIS PLAĆE</b></td>
              <td class="w-1/8 border p-2 text-center"><b>SATI</b></td>
              <td class="w-1/8 border p-2 text-right"><b>IZNOS</b></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.1. Za redoviti rad</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.1.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.1.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.4 Za prekovremeni rad</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.4.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.4.kn'] }}</td>
            </tr>
            @if($data['1.7a.h'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.7a Praznici. Blagdani, izbori</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.7a.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.7a.kn'] }}</td>
            </tr>
            @endif
            @if($data['1.7b.h'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.7b Godišnji odmor</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.7b.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.7b.kn'] }}</td>
            </tr>
            @endif
            @if($data['1.7c.h'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.7c Plaćeni dopust</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.7c.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.7c.kn'] }}</td>
            </tr>
            @endif
            @if($data['1.7d.h'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.7d Bolovanje do 42 dana</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.7d.h'] }}</td>
              <td class="w-1/8 border p-2 text-right" title="{{ $data['1.7d.t'] }}">{{ $data['1.7d.kn'] }}</td>
            </tr>
            @endif
            @if($data['1.7e.h'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.7e Dodatak za rad nedjeljom</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.7e.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.7e.kn'] }}</td>
            </tr>
            @endif
            @if($data['1.7f.h'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.7f Dodatak za rad na praznik</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.7f.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.7f.kn'] }}</td>
            </tr>
            @endif
            @if($data['1.7g.h'] != '0,00 (0min)')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.7g Dodatak za noćni rad</td>
              <td class="w-1/8 border p-2 text-center">{{ $data['1.7g.h'] }}</td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.7g.kn'] }}</td>
            </tr>
            @endif
            @if($data['1.7p.kn'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">1.7.P Nagrada za radne rezultate</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['1.7p.kn'] }}</td>
            </tr>
            @endif

            <tr>
              <td class="w-3/4 border p-2" colspan="2">2. OSTALI OBLICI RADA TEMELJEM KOJIH OSTVARUJE PRAVO NA UVEĆANJE PLAĆE PREMA KOLEKTIVNOM UGOVORU, PRAVILNIKU O RADU ILI UGOVORU O RADU I NOVČANI IZNOS PO TOJ OSNOVI (SATI PRIPRAVNOSTI)</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">
                @if($data['2.kn'] != '0,00')
                {{ $data['2.kn'] }}
                @endif
              </td>
            </tr>
            @if($data['2.8.kn'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">2.8. Stimulacija bruto</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['2.8.kn'] }} ({{ $data['extra'] }})</td>
            </tr>
            @endif

            <tr>
              <td class="w-3/4 border p-2" colspan="2">3. PROPISANI ILI UGOVORENI DODACI NA PLAĆU RADNIKA I NOVČANI IZNOSI PO TOJ OSNOVI</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['3.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">3.1. Prijevoz</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['3.1.kn'] }}</td>
            </tr>
            @if($data['3.2.kn'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">3.2. Božićnica</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['3.2.kn'] }}</td>
            </tr>
            @endif
            @if($data['3.7.kn'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">3.7. Regres za godišnji odmor</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['3.7.kn'] }}</td>
            </tr>
            @endif
            @if($data['3.10.kn'] != '0,00')
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">3.10. Topli obrok</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['3.10.kn'] }}</td>
            </tr>
            @endif

            <tr>
              <td class="w-3/4 border p-2" colspan="2">4. ZBROJENI IZNOSI PRIMITAKA PO SVIM OSNOVAMA PO STAVKAMA 1. DO 3.</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['4.kn'] }}</td>
            </tr>

            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>5. OSNOVICA ZA OBRAČUN DOPRINOSA</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"><b>{{ $data['5.kn'] }}<b></td>
            </tr>

            <tr>
              <td class="w-3/4 border p-2" colspan="2">6. VRSTE I IZNOSI DOPRINOSA ZA OBVEZNA OSIGURANJA KOJA SE OBUSTAVLJAJU IZ PLAĆ</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">6.1. za mirovinsko osiguranje na temelju generacijske solidarnosti (I. STUP)</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['6.1.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">6.2 za mirovinsko osiguranje na temelju individualne kapitalizirane štednje (II. STUP)</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['6.2.kn'] }}</td>
            </tr>

            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>7. DOHODAK</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"><b>{{ $data['7.kn'] }}</b></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2">8. OSOBNI ODBITAK 1.00 / {{ number_format($month->odbitak/100, 2, '.', '') }}</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['8.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2" colspan="2">9. POREZNA OSNOVICA</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['9.kn'] }}</td>
            </tr>

            <tr>
              <td class="w-3/4 border p-2" colspan="2">10. IZNOS PREDUJMA POREZA I PRIREZA POREZU NA DOHODAK</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['10.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">20.00% {{ $data['9.kn'] }}</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['10.20.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-12" colspan="2">Prirez {{ number_format($month->prirez/100, 2, '.', ',') }} %</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['10.prirez.kn'] }}</td>
            </tr>
            @if($data['10.1.kn'] != '0,00 (0,00)')
            <tr>
              <td class="w-3/4 border p-2" colspan="2">10.1. KOREKCIJA POREZA I PRIREZA</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['10.1.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">Korekcija poreza:</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['10.1.20.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">Korekcija prireza:</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['10.1.prirez.kn'] }}</td>
            </tr>
            @endif

            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>11. NETO PLAĆA</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"><b>{{ $data['11.kn'] }}</b></td>
            </tr>

            <tr>
              <td class="w-3/4 border p-2" colspan="2">12. NAKNADE UKUPNO</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['12.kn'] }}</td>
            </tr>

            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>13. NETO + NAKNADE</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"><b>{{ $data['13.kn'] }}</b></td>
            </tr>

            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>14. OBUSTAVE UKUPNO</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"><b>{{ $data['14.kn'] }}</b></td>
            </tr>

            <tr>
              <td class="w-3/4 border p-2" colspan="2"><b>15. IZNOS PLAĆE/NAKNADE PLAĆE ISPLAĆEN RADNIKU NA REDOVAN RAČUN</b></td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"><b>{{ $data['15.kn'] }}</b></td>
            </tr>

            <tr>
              <td class="w-3/4 border p-2" colspan="2">17.5. vrsta i iznos obustave</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right"></td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">Sindikalna članarina - {{ env('FIRM16') }}</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['17_5a.kn'] }}</td>
            </tr>
            <tr>
              <td class="w-3/4 border p-2 pl-6" colspan="2">Kredit</td>
              <td class="w-1/8 border p-2 text-center"></td>
              <td class="w-1/8 border p-2 text-right">{{ $data['17_5b.kn'] }}</td>
            </tr>
          </tbody>