<x-guest-layout>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

      <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <div class="grid grid-cols-3">

          <div class="p-6">
            <div class="flex items-center">
              <div class="ml-4 text-lg leading-7 font-semibold">BTC</div>
            </div>
            <div class="ml-12">
              <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                <div class="container" id="btc"></div>
              </div>
            </div>
          </div>

          <div class="p-6">
            <div class="flex items-center">
              <div class="ml-4 text-lg leading-7 font-semibold">ETH</div>
            </div>
            <div class="ml-12">
              <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                <div class="container" id="eth"></div>
              </div>
            </div>
          </div>

          <div class="p-6">
            <div class="flex items-center">
              <div class="ml-4 text-lg leading-7 font-semibold">BNB</div>
            </div>
            <div class="ml-12">
              <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                <div class="container" id="bnb"></div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2">

          <div class="p-6">
            <div class="flex items-center">
              <div class="ml-4 text-lg leading-7 font-semibold">USDT</div>
            </div>
            <div class="ml-12">
              <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                @if(count($symbols_usdt) > 0)
                @foreach($symbols_usdt as $symbol)
                <div class="container" id="{{$symbol}}"></div>
                @endforeach
                @else
                <p> No usdt symbols found</p>
                @endif
              </div>
            </div>
          </div>

          <div class="p-6">
            <div class="flex items-center">
              <div class="ml-4 text-lg leading-7 font-semibold">BUSD</div>
            </div>
            <div class="ml-12">
              <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
                @if(count($symbols_busd) > 0)
                @foreach($symbols_busd as $symbol)
                <div class="container" id="{{$symbol}}"></div>
                @endforeach
                @else
                <p> No busd symbols found</p>
                @endif
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
  <script>
    var bnb = '';
    var btc = '';
    var eth = '';
    var binanceSocket = new WebSocket("wss://stream.binance.com:9443/stream?streams={{ $link }}");

    binanceSocket.onmessage = function(event) {
      var message = JSON.parse(event.data);
      if (message.stream == 'bnbusdt@kline_1m') {
        bnb = message.data.k.c*1;
        document.getElementById('bnb').innerHTML = bnb;
      }
      if (message.stream == 'btcusdt@kline_1m') {
        btc = message.data.k.c*1;
        document.getElementById('btc').innerHTML = btc;
      }
      if (message.stream == 'ethusdt@kline_1m') {
        eth = message.data.k.c*1;
        document.getElementById('eth').innerHTML = eth;
      }
      document.title = btc + ' ' + bnb + ' ' + eth;
      //console.log('message', message)
      @foreach($symbols as $symbol)
      if (message.stream == '{{ strtolower($symbol) }}@kline_1m') {
        var postotak = 100 * (message.data.k.c - message.data.k.o) / message.data.k.o;
        if (postotak > 0.7) {
          document.getElementById('{{ $symbol }}').innerHTML = '{{ $symbol }} ' + (message.data.k.c*1) + '(' + postotak + '%)';
        } else {
          document.getElementById('{{ $symbol }}').innerHTML = '';
        }
      }
      @endforeach

      //var candlestick = message.k;

      //console.log('candlestick', candlestick)

    }

  </script>
</x-guest-layout>
