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
    var binanceSocket = new WebSocket("wss://stream.binance.com:9443/stream?streams=adabusd@kline_1m/bnbbusd@kline_1m/ethbusd@kline_1m/maticbusd@kline_1m/btcbusd@kline_1m");
    var ada = {{$balance['ADA']->price}};
    var bnb = {{$balance['BNB']->price}};
    var eth = {{$balance['ETH']->price}};
    var busd = {{$balance['BUSD']->price}};
    var matic = {{$balance['MATIC']->price}};
    var btc = {{$balance['BTC']->price}};
    // Create our number formatter.
    var formatter = new Intl.NumberFormat('hr-HR', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });
    binanceSocket.onmessage = function(event) {
      var message = JSON.parse(event.data);
      if (message.stream == 'adabusd@kline_1m') {
        ada = message.data.k.c * {{$balance['ADA']->total}} * {{$busd_kn}};
        document.getElementById('ADA').innerHTML = formatter.format(ada,2);
      }
      if (message.stream == 'bnbbusd@kline_1m') {
        bnb = message.data.k.c * {{$balance['BNB']->total}} * {{$busd_kn}};
        document.getElementById('BNB').innerHTML = formatter.format(bnb,2);
      }
      if (message.stream == 'ethbusd@kline_1m') {
        eth = message.data.k.c * {{$balance['ETH']->total}} * {{$busd_kn}};
        document.getElementById('ETH').innerHTML = formatter.format(eth,2);
      }
      if (message.stream == 'maticbusd@kline_1m') {
        matic = message.data.k.c * {{$balance['MATIC']->total}} * {{$busd_kn}};
        document.getElementById('MATIC').innerHTML = formatter.format(matic,2);
      }
      if (message.stream == 'btcbusd@kline_1m') {
        btc = message.data.k.c * {{$balance['BTC']->total}} * {{$busd_kn}};
        document.getElementById('BTC').innerHTML = formatter.format(btc,2);
      }
      var total = formatter.format(ada + bnb + eth + busd + matic + btc,2);
      document.getElementById('total').innerHTML = total;
      document.title = total;
    }

  </script>
</x-app-layout>
