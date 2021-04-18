<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Trades') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          @if(count($trades) > 0)
          @foreach($trades as $trade)
          <div class="container">
            {{$trade->time}} {{$trade->symbol}} {{$trade->price}} {{$trade->qty}}
            {{$trade->commission}} {{$trade->commissionAsset}} {{$trade->isBuyer}} {{$trade->isMaker}}
          </div>
          @endforeach
          @else
          <p> No trades found</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
