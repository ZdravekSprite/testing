<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Portfolio') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-gray-100 border-b border-gray-200">
          <div id="app">
            @if(count($balance) > 0)
            @foreach($balance as $coin => $asset)
            <p title="{{$asset->name}}">{{$asset->name}}: {{$asset->total}} {{$coin}} ({{round($asset->price,2)}} kn)</p>
            @endforeach
            <p> Total: {{round($total,2)}} kn</p>
            @else
            <p> No assets found</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
