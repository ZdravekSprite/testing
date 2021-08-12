<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Binance') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
          <div class="grid grid-cols-1 md:grid-cols-2">

            <div class="p-6">
              <form method="POST" action="{{ route('testNewOrder') }}">
                @csrf
                <div class="mt-4">
                  <input id="buy_price" step="0.00001" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="price" value="{{old('buy_price')?? '0'}}" />
                  <input id="buy_quantity" step="0.1" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="quantity" value="{{old('buy_quantity')?? '0'}}" />
                  <input id="buy_quoteOrderQty" step="0.000001" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="quoteOrderQty" value="{{old('buy_quoteOrderQty')?? '0'}}" />
                </div>
                <input type="hidden" name="symbol" value="{{ $symbol }}">
                <input type="hidden" name="side" value="BUY">
                <input type="hidden" name="type" value="LIMIT">
                <div class="flex items-center justify-end mt-4">
                  <x-button class="ml-4">
                    {{ __('Buy') }}
                  </x-button>
                </div>
              </form>
            </div>

            <div class="p-6">
              <form method="POST" action="{{ route('testNewOrder') }}">
                @csrf
                <div class="mt-4">
                  <input id="sell_price" step="0.00001" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="price" value=0 />
                  <input id="sell_quantity" step="0.1" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="quantity" value=0 />
                  <input id="sell_quoteOrderQty" step="0.000001" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="quoteOrderQty" value=0 />
                </div>
                <input type="hidden" name="symbol" value="{{ $symbol }}">
                <input type="hidden" name="side" value="SELL">
                <input type="hidden" name="type" value="LIMIT">
                <div class="flex items-center justify-end mt-4">
                  <x-button class="ml-4">
                    {{ __('Sell') }}
                  </x-button>
                </div>
              </form>
            </div>

          </div>
        </div>

        <div class="p-6 bg-white border-b border-gray-200" id="container">
        </div>
        <div class="floating-tooltip-2" id="tooltip">
        </div>

        <div id="app">
        <example-component></example-component>
        </div>

      </div>
    </div>
  </div>
  <script>
    document.title = '{{ config('app.name', 'Laravel') }} - B - dashboard';
    var base = '{{ $base }}';
    var dec1 = {{ $dec1 }};
    var quote = '{{ $quote }}';
    var dec2 = {{ $dec2 }};

    var container = document.getElementById("container");
    var toolTip = document.getElementById("tooltip");

    var chartWidth = container.offsetWidth - 48;
    var chartHeight = 600;

    var toolTipWidth = 100;
    var toolTipHeight = 80;
    var toolTipMargin = 15;

    var klines;

    var chart = LightweightCharts.createChart(container, {
      width: chartWidth,
      height: chartHeight,
      layout: {
        backgroundColor: '#000000',
        textColor: 'rgba(255, 255, 255, 0.9)',
      },
      grid: {
        vertLines: {
          color: 'rgba(197, 203, 206, 0.5)',
        },
        horzLines: {
          color: 'rgba(197, 203, 206, 0.5)',
        },
      },
      crosshair: {
        mode: LightweightCharts.CrosshairMode.Normal,
      },
      priceScale: {
        borderColor: 'rgba(197, 203, 206, 0.8)',
      },
      rightPriceScale: {
        scaleMargins: {
          top: 0.2,
          bottom: 0.1,
        },
      },
      timeScale: {
        rightOffset: 2,
        borderColor: 'rgba(197, 203, 206, 0.8)',
        timeVisible: true,
        secondsVisible: false,
      },
      watermark: {
        color: 'rgba(170, 175, 180, 0.5)',
        visible: true,
        text: base + quote,
        fontSize: 22,
        horzAlign: 'left',
        vertAlign: 'top',
      },
    });

    // update tooltip
    chart.subscribeCrosshairMove(function(param) {
      if (!param.time || param.point.x < 0 || param.point.x > chartWidth || param.point.y < 0 || param.point.y > chartHeight) {
        toolTip.style.display = 'none';
        return;
      }

      toolTip.style.display = 'block';
      var price = param.seriesPrices.get(candleSeries);
      var pres = ((price.close - price.open ) / price.open );
      var d = new Date(param.time * 1000);
      var datestring = d.getDate()  + "-" + (d.getMonth()+1) + "-" + d.getFullYear();
      var timestring = d.getHours() + ":" + d.getMinutes();
      toolTip.innerHTML = '<div style="font-size: 10px; color: rgba(255, 70, 70, 1)">' + base + quote + '</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + datestring + '</div>' +
        '<div style="font-size: 12px; margin: 2px 0px">' + timestring + '</div>' +
        '<div style="font-size: 12px; margin: 2px 0px">' + (pres*100).toFixed(2) + '%</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + price.high*1 + '</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + price.open*1 + '</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + price.close*1 + '</div>' +
        '<div style="font-size: 10px; margin: 2px 0px">' + price.low*1 + '</div>';

      var y = container.offsetTop + param.point.y;
      var x = container.offsetLeft + param.point.x;

      var left = x + toolTipMargin;
      if (left > chartWidth - toolTipWidth) {
        left = x - toolTipMargin - toolTipWidth;
      }

      var top = y + toolTipMargin;
      //if (top > chartHeight - toolTipHeight) {
        top = y - toolTipHeight - toolTipMargin;
      //}

      toolTip.style.left = left + 'px';
      toolTip.style.top = top + 'px';
    });

    var candleSeries = chart.addCandlestickSeries({
      upColor: '#00ff00',
      downColor: '#ff0000',
      borderDownColor: 'rgba(255, 144, 0, 1)',
      borderUpColor: 'rgba(255, 144, 0, 1)',
      wickDownColor: 'rgba(255, 144, 0, 1)',
      wickUpColor: 'rgba(255, 144, 0, 1)',
      priceFormat: { type: 'price', minMove: 0.0001, precision: 4 },
      scaleMargins: {
        top: 1,
        bottom: 0.05,
      },
    });

    const histogramSeries = chart.addHistogramSeries({
      color: '#26a69a',
      priceFormat: {
        type: 'volume',
      },
      priceScaleId: '',
      scaleMargins: {
        top: 0.90,
        bottom: 0,
      },
    });

    const areaSeries = chart.addAreaSeries({
      topColor: 'rgba(21, 146, 230, 0.4)',
      bottomColor: 'rgba(21, 146, 230, 0)',
      lineColor: 'rgba(21, 146, 230, 1)',
      lineStyle: 0,
      lineWidth: 3,
      crosshairMarkerVisible: false,
      crosshairMarkerRadius: 3,
    });

    var list = [];
    var rang = 60;
    fetch('https://api.binance.com/api/v3/klines?symbol=' + base + quote + '&interval=1m&limit=1000')
      .then((res) => res.json())
      .then((res) => {
        klines = res.map(function(x) {
          return {
            time: x[0] / 1000 + 60*60*2,
            open: x[1],
            high: x[2],
            low: x[3],
            close: x[4],
            value: x[5]*x[4],
            color: x[1] > x[4] ? 'rgba(255,82,82, 0.8)' : 'rgba(0, 150, 136, 0.8)'
          };
        });
        var candle_objs = res.map(function(x) {
          return {
            time: x[0] / 1000 + 60*60*2,
            open: x[1],
            high: x[2],
            low: x[3],
            close: x[4],
            value: x[5]*x[4],
            color: x[1] > x[4] ? 'rgba(255,82,82, 0.8)' : 'rgba(0, 150, 136, 0.8)'
          };
        });
        var histogram_objs = res.map(function(x) {
          return {
            time: x[0] / 1000 + 60*60*2,
            value: x[5]*x[4],
            color: x[1] > x[4] ? 'rgba(255,82,82, 0.8)' : 'rgba(0, 150, 136, 0.8)'
          };
        });
        var area_objs = res.map(function(x) {
          list.push(x[4]);
          var dev = list.length < rang ? list.length : rang;
          return {
            list: list.slice(Math.max(list.length - rang, 0)),
            time: x[0] / 1000 + 60*60*2,
            value: list.slice(Math.max(list.length - rang, 0)).reduce((a, b) => a*1 + b*1, 0) / dev //x[4]
          };
        });
        console.log('area',area_objs);
        candleSeries.setData(candle_objs);
        histogramSeries.setData(histogram_objs);
        areaSeries.setData(area_objs);
      })

    var binanceSocket = new WebSocket("wss://stream.binance.com:9443/ws/" + base.toLowerCase() + quote.toLowerCase() + "@kline_1m");

    binanceSocket.onmessage = function(event) {
      //console.log('test',klines);
      var message = JSON.parse(event.data);
      //console.log('message', message)
      var candlestick = message.k;
      candleSeries.update({
        time: candlestick.t / 1000 + 60*60*2,
        open: candlestick.o,
        high: candlestick.h,
        low: candlestick.l,
        close: candlestick.c,
      });
      histogramSeries.update({
        time: candlestick.t / 1000 + 60*60*2,
        value: candlestick.v * candlestick.c,
        color: candlestick.o > candlestick.c ? 'rgba(255,82,82, 0.8)' : 'rgba(0, 150, 136, 0.8)'
      });
      if (candlestick.x) list.push(candlestick.c);
      //console.log('price', list.slice(Math.max(list.length - rang, 0)))
      //param.seriesPrices.get(areaSeries);
      var off = 2;
      var busd10 = 10/candlestick.c;
      var pow1 = Math.pow(10,dec1);
      var pow2 = Math.pow(10,dec2);
      var down = Math.ceil(busd10*pow1*(100+off)/100)/pow1;
      var busd10down = Math.ceil(1/down*10*pow2)/pow2;
      var up = Math.floor(busd10*pow1*(100-off)/100)/pow1;
      var busd10up = Math.ceil(1/up*10*pow2)/pow2;

      document.getElementById("buy_price").value = busd10down;
      document.getElementById("buy_quantity").value = down;
      document.getElementById("buy_quoteOrderQty").value = busd10down*down;
      document.getElementById("sell_price").value = busd10up;
      document.getElementById("sell_quantity").value = up;
      document.getElementById("sell_quoteOrderQty").value = busd10up*up;

      var temp_val
      areaSeries.update({
        list: list.slice(Math.max(list.length - rang, 0)),
        time: candlestick.t / 1000 + 60*60*2,
        value: candlestick.x ? (list.slice(Math.max(list.length - rang, 0)).reduce((a, b) => a*1 + b*1, 0) / rang) : ((list.slice(Math.max(list.length - rang + 1, 0)).reduce((a, b) => a*1 + b*1, 0) + candlestick.c*1) / rang) 
      });
    }

  </script>
</x-app-layout>
