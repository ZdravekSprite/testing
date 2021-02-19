<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Days') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          Days index!
          @if(count($days) > 0)
          @foreach($days as $day)
          <div class="well">
          <span class="badge badge-light">{{$day->id}}.</span><h3><a href="/days/{{$day->id}}">{{$day->day}}</a></h3><span class="badge badge-info">{{$day->user->name}}</span>
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