<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Days') }}
    </h2>
    <x-responsive-nav-link :href="route('days.create')">
      {{ __('Create day') }}
    </x-responsive-nav-link>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          Days index!
          @if(count($days) > 0)
          @foreach($days as $day)
          <div class="p-6">
            <div class="flex items-center">
                <div class="ml-4 text-lg leading-7 font-semibold" title="{{$day->user->name}}"><a href="/days/{{$day->id}}" class="underline text-gray-900 dark:text-white">{{$day->day}}</a></div>
            </div>

            <div class="ml-12">
                <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                  {{$day->day}}
                </div>
            </div>
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