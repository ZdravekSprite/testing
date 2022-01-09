<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight" title="{{$month->month}}">
      {{ __('Mjesec') }}
      {{ $month->slug() }}
      <a href="{{ route('months.edit', ['month' => $month->slug()]) }}" title="Izmjeni">
        <svg style="display: inline;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
          <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
        </svg>
      </a>
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 shadow">
      <div class="px-6">
        <p>Bruto: {{number_format($month->bruto/100, 2, ',', ' ')}}kn</p>
        <p>Odbitak: {{$month->odbitak ? number_format($month->odbitak/100, 2, ',', ' ') : number_format($month->last('odbitak')/100, 2, ',', ' ')}}kn</p>
        <p>Prirez: {{$month->prirez ? number_format($month->prirez/100, 2, ',', ' ') : number_format($month->last('prirez')/100, 2, ',', ' ')}}%</p>
        <p>Minuli rad: {{$month->minuli ? number_format($month->minuli/10, 2, ',', ' ') : number_format($month->last('minuli')/10, 2, ',', ' ')}}%</p>
      </div>
      <div>
        <h3>Neto:<h3>
            <div class="px-6">
              <p>Regres: {{number_format($month->regres/100, 2, ',', ' ')}}kn</p>
              <p>Božićnica: {{number_format($month->bozicnica/100, 2, ',', ' ')}}kn</p>
              <p>Prigodna: {{number_format($month->prigodna/100, 2, ',', ' ')}}kn</p>
            </div>
      </div>
      <div class="px-6">
        <p>Prijevoz: {{$month->prijevoz ? number_format($month->prijevoz/100, 2, ',', ' ') : number_format($month->last('prijevoz')/100, 2, ',', ' ')}}kn</p>
        <p>Prehrana: {{number_format($month->prehrana/100, 2, ',', ' ')}}kn</p>
        <p>Nagrada: {{number_format($month->nagrada/100, 2, ',', ' ')}}kn</p>
      </div>
    </div>
    <tr>
      <div class="grid grid-cols-1 md:grid-cols-3">
        <div>
          <p>Plaćeni prekovremeni: {{number_format($month->prekovremeni, 2, ',', ' ')}} sati</p>
        </div>
        <div>
          <p>Bruto stimulacija: {{number_format($month->stimulacija/100, 2, ',', ' ')}} kn</p>
        </div>
        <div>
          <p>Stari prekovremeni: {{number_format($month->stari/100, 2, ',', ' ')}} sati</p>
        </div>
      </div>
  </x-slot>

  @include('months.list')
  @include('months.payroll')
</x-app-layout>
