<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Mjeseci') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <table class="table-auto w-full text-sm md:text-base">
            <thead>
              <tr>
                <th>mjesec</th>
                <th>bruto</th>
                <th class="hidden md:table-cell">odbitak</th>
                <th class="hidden md:table-cell">prirez</th>
                @hasrole(env('FIRM2'))
                <th class="hidden md:table-cell">minuli</th>
                @endhasrole
                <th class="break-all md:break-normal">prekovremeni</th>
                <th class="break-all md:break-normal">stimulacija</th>
                <th class="break-all md:break-normal">stari prekovremeni</th>
                <th>nagrada</th>
                <th class="hidden md:table-cell">prijevoz</th>
                <th class="hidden md:table-cell">prehrana</th>
                <th>regres</th>
                <th>božićnica</th>
                <th class="w-16"></th>
              </tr>
            </thead>
            <tbody>
              @if(count($months) > 0)
              @foreach($months as $m)
              <tr>
                <td><a href="{{ route('months.show', ['month' => $m->slug()]) }}" title="{{$m->slug()}}">{{$m->slug()}}</a></td>
                <td>{{$m->bruto ? number_format($m->bruto/100, 2, ',', '') : number_format($m->last('bruto')/100, 2, ',', '')}}</td>
                <td class="hidden md:table-cell">{{$m->odbitak ? number_format($m->odbitak/100, 2, ',', '') : number_format($m->last('odbitak')/100, 2, ',', '')}}</td>
                <td class="hidden md:table-cell">{{$m->prirez ? number_format($m->prirez/100, 2, ',', '') : number_format($m->last('prirez')/100, 2, ',', '')}}</td>
                @hasrole(env('FIRM2'))
                <td class="hidden md:table-cell">{{$m->minuli ? number_format($m->minuli/10, 2, ',', '') : number_format($m->last('minuli')/10, 2, ',', '')}}%</td>
                @endhasrole
                <td>{{$m->prekovremeni ?? 0}} ( {{number_format($m->hoursNorm()->min / 60 - $m->hoursNorm()->Work ?? 0, 2, ',', '')}} )</td>
                <td>{{$m->stimulacija ? number_format($m->stimulacija/100, 2, ',', '') : 0}} ( {{ number_format($m->stimulacija / 100 / round((($m->bruto ?? $m->last('bruto')) / 100 / $m->hoursNorm()->All), 2) / 1.5, 2, ',', '') }} )</td>
                <td>{{$m->stari ? number_format($m->stari/60, 2, ',', '.') : 0}}</td>
                <td>{{$m->nagrada ? number_format($m->nagrada/100, 2, ',', '') : 0}}</td>
                <td class="hidden md:table-cell">{{$m->prijevoz ? number_format($m->prijevoz/100, 2, ',', '') : number_format($m->last('prijevoz')/100, 2, ',', '')}}</td>
                <td class="hidden md:table-cell">{{$m->prehrana ? number_format($m->prehrana/100, 2, ',', '') : number_format($m->last('prehrana')/100, 2, ',', '')}}</td>
                <td>{{$m->regres ? number_format($m->regres/100, 2, ',', '') : 0}}</td>
                <td>{{$m->bozicnica ? number_format($m->bozicnica/100, 2, ',', '') : 0}}</td>
                <td><a class="float-left" href="{{ route('months.edit', ['month' => $m->slug()]) }}" title="Izmjeni">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                      <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
                    </svg>
                  </a>

                  <a class="float-right" style="color:red" href="{{ route('months.destroy', ['month' => $m->slug()]) }}" onclick="event.preventDefault(); if(confirm('Are you sure?')) document.getElementById('delete-form-{{ $m->month }}').submit();" title="Izbriši">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                      <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                    </svg>
                  </a>
                  <form id="delete-form-{{ $m->month }}" action="{{ route('months.destroy', ['month' => $m->slug()]) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                  </form>
                </td>
              </tr>
              @endforeach
              @else
              <p> No months found</p>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @include('months.list')
</x-app-layout>
