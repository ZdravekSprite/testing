<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Platna lista') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <div class="flex justify-center">
            Bruto: {{$data['bruto']}},
            Prijevoz: {{$data['prijevoz']}},
            Odbitak: {{$data['odbitak']}},
            Prirez: {{$data['prirez']}},
            Prekovremeni:
            <a href="{{ route('lista', ['month' => $month['x']->format('m.Y'), 'prekovremeni' => 0]) }}">0</a>
            <a href="{{ route('lista', ['month' => $month['x']->format('m.Y'), 'prekovremeni' => 8]) }}">8</a>
            <a href="{{ route('lista', ['month' => $month['x']->format('m.Y'), 'prekovremeni' => 16]) }}">16</a>
            <a href="{{ route('lista', ['month' => $month['x']->format('m.Y'), 'prekovremeni' => 24]) }}">24</a>
            <a href="{{ route('lista', ['month' => $month['x']->format('m.Y'), 'prekovremeni' => 32]) }}">32</a>
          </div>
          <div class="flex justify-center">
            <a href="{{ route('lista', ['month' => $month['-']->format('m.Y')]) }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z" />
              </svg>
            </a>
            <a class="mx-auto" href="{{ route('lista', [$month['x']->format('m.Y')]) }}">
              Platna lista za {{$month['x']->format('m.Y')}}!
            </a>
            <a href="{{ route('lista', [$month['+']->format('m.Y')]) }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-right-square" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z" />
              </svg>
            </a>
          </div>
          <table class="table-fixed">
            <thead>
              <tr>
                <th class="w-1/2 text-left"><b>OBRAČUN ISPLAĆENE PLAĆE</b></th>
                <th class="w-1/2 text-right" colspan="3"><b>Obrazac IP1</b></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="border p-2">
                  <ul>
                    <li><b>I. PODACI O POSLODAVCU</b></li>
                    <li>1. Tvrtka/ Ime i prezime: ____</li>
                    <li>2. Sjedište / Adresa: ____</li>
                    <li>3. Osobni identifikacijski broj: ____</li>
                    <li>4. IBAN broj računa ____ kod ____</li>
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
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">1.7a Praznici. Blagdani, izbori</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.7a.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.7a.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">1.7d Bolovanje do 42 dana</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.7d.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.7d.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">1.7e Dodatak za rad nedjeljom</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.7e.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.7e.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-6" colspan="2">1.7f Dodatak za rad na praznik</td>
                <td class="w-1/8 border p-2 text-center">{{ $data['1.7f.h'] }}</td>
                <td class="w-1/8 border p-2 text-right">{{ $data['1.7f.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2" colspan="2">2. OSTALI OBLICI RADA TEMELJEM KOJIH OSTVARUJE PRAVO NA UVEĆANJE PLAĆE PREMA KOLEKTIVNOM UGOVORU, PRAVILNIKU O RADU ILI UGOVORU O RADU I NOVČANI IZNOS PO TOJ OSNOVI (SATI PRIPRAVNOSTI)</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right"></td>
              </tr>
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
                <td class="w-3/4 border p-2" colspan="2">8. OSOBNI ODBITAK 1.00 / 4000.00</td>
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
                <td class="w-3/4 border p-2 pl-6" colspan="2">20.00% 1363.41</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['10.20.kn'] }}</td>
              </tr>
              <tr>
                <td class="w-3/4 border p-2 pl-12" colspan="2">Prirez 12.00 %</td>
                <td class="w-1/8 border p-2 text-center"></td>
                <td class="w-1/8 border p-2 text-right">{{ $data['10.prirez.kn'] }}</td>
              </tr>
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
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
