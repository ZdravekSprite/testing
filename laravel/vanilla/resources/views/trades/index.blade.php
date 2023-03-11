<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Trades') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <table class="table-auto w-full">
            <thead>
              <tr>
                <th>
                </th>
                <th>
                </th>
                @if(count($symbols) > 0)
                @foreach($trades[0]->assets as $coin => $asset)
                <th title="{{$asset->name}} {{isset($balance[$coin]) ? $balance[$coin] : ''}}">{{$coin}}</th>
                @endforeach
                @else
                <p> No symbols found</p>
                @endif
                <th>
                </th>
              </tr>
            </thead>
            <tbody class="text-sm">
              @if(count($trades) > 0)
              @foreach($trades as $key => $trade)
              <tr style="color:@if($trade->orderListId == -2) @if($trade->isBuyer) blue @else tomato @endif @else @if($trade->isBuyer) red @else green @endif @endif;" >
                <td title="{{$trade->price}} {{$trade->qty}} {{$trade->isBuyer ? 'BUY' : 'SELL'}} {{$trade->quoteQty}} {{$trade->commission}} {{$trade->commissionAsset}}">
                  {{gmdate("Y-m-d H:i:s", $trade->time / 1000)}} {{$trade->symbol}}
                </td>
                <td>
                  {{round($trade->total_kn, 2)}}kn
                </td>
                @if($trade->assets)
                @foreach($trade->assets as $coin => $asset)
                  <td>{{round($asset->total, 8)}}</td>
                @endforeach
                @endif
              </tr>
              @endforeach
              @else
              <p> No trades found</p>
              @endif
            </tbody>
          </table>
          {{ $trades->links() }}
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
