<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ $title }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <table class="table-auto w-full">
            <thead>
              <tr>
                <th>{{ __('datum') }}</th>
                <th>no1</th>
                <th>no2</th>
                <th>no3</th>
                <th>no4</th>
                <th>no5</th>
                <th>bo1</th>
                <th>bo2</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($draws) > 0)
              @foreach($draws as $draw)
              <tr>
                <th>{{ $draw->date }}</th>
                <td>{{ $draw->no01 }}</td>
                <td>{{ $draw->no02 }}</td>
                <td>{{ $draw->no03 }}</td>
                <td>{{ $draw->no04 }}</td>
                <td>{{ $draw->no05 }}</td>
                <td>{{ $draw->bo01 }}</td>
                <td>{{ $draw->bo02 }}</td>
                <td></td>
                <td>
                  <a class="float-left" href="{{ route('draws.edit', ['draw' => $draw]) }}" title="Izmjeni">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                      <path d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
                    </svg>
                  </a>
                </td>
              </tr>
              @endforeach
              @else
              <p> No draws found</p>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
