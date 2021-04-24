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
                @if(count($symbols) > 0)
                @foreach($symbols as $key => $symbol)
                <th>{{$key}}</th>
                @endforeach
                @else
                <p> No symbols found</p>
                @endif
                <th>
                </th>
              </tr>
            </thead>
            <tbody>
              @if(count($trades) > 0)
              @foreach($trades as $trade)
              <tr style="color:@if($trade->orderListId == -2) @if($trade->isBuyer) blue @else tomato @endif @else @if($trade->isBuyer) red @else green @endif @endif;" >
                <td title="{{$trade->qty}} {{$trade->isBuyer ? 'BUY' : 'SELL'}} {{$trade->quoteQty}} {{$trade->commission}} {{$trade->commissionAsset}}">
                  {{gmdate("Y-m-d H:i:s", $trade->time / 1000)}} {{$trade->symbol}}
                </td>
                @foreach($trade->assets as $asset)
                <td>
                  {{round($asset, 8)}}
                </td>
                @endforeach
                <td title="1$ = {{$trade->hnb->where('valuta', '=', 'USD')->first()->kupovni_tecaj}}kn
                  1EUR = {{$trade->hnb->where('valuta', '=', 'EUR')->first()->kupovni_tecaj}}kn">
                  $/EUR
                </td>
              </tr>
              @endforeach
              @else
              <p> No trades found</p>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
