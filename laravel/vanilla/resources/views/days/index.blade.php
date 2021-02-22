<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Evidencija radnih sati') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          Evidencija radnih sati!
          @if(count($days) > 0)
          @foreach($days as $day)
          <div class="container">
            <a class="float-left" href="/days/{{$day->date->format('d.m.Y')}}">{{$day->date->format('d.m.Y')}}</a>
            <div class="float-left relative bg-indigo-100" style="width: 75%; min-height: 18px;" title={{$day->date->format('d.m.Y')}}>
              <div class="absolute bg-indigo-700 min-h-full" style="width: {{($day->night_duration->hour*60 + $day->night_duration->minute)/1440*100}}%;"></div>
              <div class="absolute bg-indigo-500 min-h-full" style="margin-left: {{($day->start->hour*60 + $day->start->minute)/1440*100}}%; width: {{($day->duration->hour*60 + $day->duration->minute)/1440*100}}%;"></div>
            </div>
            <a href="/days/{{$day->date->format('d.m.Y')}}/edit">edit</a>
            <i class="icon-trash"></i>
            <a style="color:black" href="{{ route('days.destroy', ['day' => $day]) }}" onclick="event.preventDefault();
    document.getElementById('delete-form-{{ $day->date->format('d.m.Y') }}').submit();">
              delete
            </a>

            <form id="delete-form-{{ $day->date->format('d.m.Y') }}" action="{{ route('days.destroy', ['day' => $day]) }}" method="POST" style="display: none;">
              @csrf
            @method('DELETE')
            </form>
          </div>
          @endforeach
          @else
          <p> No days found</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
