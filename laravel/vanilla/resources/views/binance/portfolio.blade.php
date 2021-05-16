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
            <p title="{{$asset->name}} {{round($asset->price,2)}}">{{$asset->name}}: {{$asset->total}} {{$coin}} (<span id="{{$coin}}">{{round($asset->price,2)}}</span> kn)</p>
            @endforeach
            <p> Total: <span id="total">{{round($total,2)}}</span> kn</p>
            @else
            <p> No assets found</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
    <script>
    var binanceSocket = new WebSocket("wss://stream.binance.com:9443/stream?streams=adabusd@kline_1m/bnbbusd@kline_1m/ethbusd@kline_1m/maticbusd@kline_1m/btcbusd@kline_1m/eurbusd@kline_1m");
    @foreach($balance as $coin => $asset)
    var {{strtolower($coin)}} = {{$balance[$coin]->price}};
    @endforeach
    // Create our number formatter.
    var formatter = new Intl.NumberFormat('hr-HR', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
    var busd_kn = {{$busd_kn}};
    binanceSocket.onmessage = function(event) {
      var message = JSON.parse(event.data);
      @foreach($balance as $coin => $asset)
      if (message.stream == '{{strtolower($coin)}}busd@kline_1m') {
        {{strtolower($coin)}} = message.data.k.c * {{$balance[$coin]->total}} * busd_kn;
        document.getElementById('{{$coin}}').innerHTML = formatter.format({{strtolower($coin)}},2);
      }
      @endforeach
      if (message.stream == 'eurbusd@kline_1m') {
        busd_kn = {{$eur_kn}} / message.data.k.c;
        busd = {{$balance['BUSD']->total}} * busd_kn;
        document.getElementById('BUSD').innerHTML = formatter.format(busd,2);
      }
      var total = 0;
      @foreach($balance as $coin => $asset)
      total = total + {{strtolower($coin)}};
      @endforeach
      document.getElementById('total').innerHTML = formatter.format(total,2);
      document.title = formatter.format(total,2);
    }

  </script>
</x-app-layout>
